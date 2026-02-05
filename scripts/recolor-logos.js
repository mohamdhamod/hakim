#!/usr/bin/env node
/*
  Recolor specific beige shades in logo PNGs to a single color.
  - For favicon.png: map beige -> dominant blue in the image
  - For white-logo.png: map beige -> pure white
  This script is idempotent and will create .bak backups on first run.
*/
import fs from 'fs';
import path from 'path';
import { PNG } from 'pngjs';

const ROOT = path.resolve(process.cwd());
const logosDir = path.join(ROOT, 'public', 'images', 'logos');
const files = [
  { name: 'blue-logo.png', target: 'dominant' },
  { name: 'white-logo.png', target: 'white' },
];

// Beige reference colors found in current assets (approx) plus tolerance
const BEIGE_REFS = [
  [210, 190, 150], // generic beige
  [222, 198, 156],
  [216, 186, 136],
  [205, 175, 129],
  [214, 193, 147]
];
const TOLERANCE = 32; // +/- per channel

function isNearColor(r, g, b, [cr, cg, cb], tol = TOLERANCE) {
  return Math.abs(r - cr) <= tol && Math.abs(g - cg) <= tol && Math.abs(b - cb) <= tol;
}

function isBeige(r, g, b) {
  return BEIGE_REFS.some(ref => isNearColor(r, g, b, ref));
}

function rgbKey(r, g, b) { return `${r},${g},${b}`; }
function parseKey(k) { return k.split(',').map(n => parseInt(n, 10)); }

function findDominantNonBeigeColor(png) {
  const counts = new Map();
  const { data, width, height } = png;
  for (let y = 0; y < height; y++) {
    for (let x = 0; x < width; x++) {
      const idx = (width * y + x) << 2;
      const r = data[idx], g = data[idx + 1], b = data[idx + 2], a = data[idx + 3];
      if (a < 10) continue; // ignore transparent
      if (isBeige(r, g, b)) continue; // skip beige cluster
      // ignore near-white when searching for dominant blue for blue-logo
      if (r > 240 && g > 240 && b > 240) continue;
      const key = rgbKey(r, g, b);
      counts.set(key, (counts.get(key) || 0) + 1);
    }
  }
  let bestKey = null, bestCount = -1;
  for (const [key, count] of counts.entries()) {
    if (count > bestCount) { bestCount = count; bestKey = key; }
  }
  return bestKey ? parseKey(bestKey) : [0, 32, 64]; // fallback deep blue-ish
}

function recolorPng(inputPath, outputPath, targetMode) {
  return new Promise((resolve, reject) => {
    fs.createReadStream(inputPath)
      .pipe(new PNG({ filterType: 4 }))
      .on('parsed', function() {
        const png = this;
        let targetColor = [255, 255, 255];
        if (targetMode === 'dominant') {
          targetColor = findDominantNonBeigeColor(png);
        } else if (targetMode === 'white') {
          targetColor = [255, 255, 255];
        }

        const { data, width, height } = png;
        let changed = 0;
        for (let y = 0; y < height; y++) {
          for (let x = 0; x < width; x++) {
            const idx = (width * y + x) << 2;
            const r = data[idx], g = data[idx + 1], b = data[idx + 2], a = data[idx + 3];
            if (a < 10) continue; // ignore transparent
            if (!isBeige(r, g, b)) continue;
            data[idx] = targetColor[0];
            data[idx + 1] = targetColor[1];
            data[idx + 2] = targetColor[2];
            // keep alpha as-is
            changed++;
          }
        }

        if (changed === 0) {
          console.log(`No beige pixels found in ${path.basename(inputPath)} (nothing to change).`);
        } else {
          console.log(`Recolored ${changed} pixels in ${path.basename(inputPath)} -> ${targetMode}`);
        }

        png.pack().pipe(fs.createWriteStream(outputPath))
          .on('finish', () => resolve())
          .on('error', reject);
      })
      .on('error', reject);
  });
}

async function run() {
  // Ensure pngjs is available
  // Back up originals
  for (const f of files) {
    const p = path.join(logosDir, f.name);
    const bak = p + '.bak';
    if (!fs.existsSync(p)) {
      console.warn(`File not found: ${p}`);
      continue;
    }
    if (!fs.existsSync(bak)) {
      fs.copyFileSync(p, bak);
    }
  }

  for (const f of files) {
    const p = path.join(logosDir, f.name);
    if (!fs.existsSync(p)) continue;
    await recolorPng(p, p, f.target);
  }

  console.log('Done.');
}

run().catch(err => {
  console.error(err);
  process.exit(1);
});
