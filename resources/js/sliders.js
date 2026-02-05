// Modern sliders initialization using Swiper (lazy loaded)
// Import Swiper CSS via JS so Vite bundles styles correctly
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-coverflow';

function loadSwiper() {
  const base = import('swiper');
  const mods = import('swiper/modules');
  return Promise.all([base, mods]).then(([baseMod, modsMod]) => {
    const Swiper = baseMod.default || baseMod.Swiper || baseMod;
    const modules = {
      Navigation: modsMod.Navigation,
      Pagination: modsMod.Pagination,
      Autoplay: modsMod.Autoplay,
      EffectFade: modsMod.EffectFade,
      EffectCreative: modsMod.EffectCreative,
      EffectCoverflow: modsMod.EffectCoverflow,
      Thumbs: modsMod.Thumbs,
    };
    return { Swiper, modules };
  });
}

function initCategorySliders(Swiper, modules) {
  document.querySelectorAll('.swiper[data-swiper="category"]').forEach(el => {
    const nextEl = el.querySelector('.swiper-button-next');
    const prevEl = el.querySelector('.swiper-button-prev');
    const pagEl = el.querySelector('.swiper-pagination');
    const hasNavigation = !!(nextEl && prevEl);
    const slideCount = el.querySelectorAll('.swiper-slide').length;
    const enableLoop = slideCount >= 3;
    const modulesToUse = [modules.Pagination, modules.Autoplay, modules.EffectCoverflow];

    if (hasNavigation) {
      modulesToUse.push(modules.Navigation);
    }

    // eslint-disable-next-line no-new
    new Swiper(el, {
      modules: modulesToUse,
      loop: enableLoop,
      rewind: !enableLoop,
      watchOverflow: true,
      speed: 650,
      autoplay: { delay: 3800, disableOnInteraction: false },
      grabCursor: true,
      effect: 'coverflow',
      coverflowEffect: {
        rotate: 6,
        stretch: 0,
        depth: 120,
        modifier: 1,
        slideShadows: true,
      },
      centeredSlides: false,
      slidesPerView: 1,
      spaceBetween: 20,
      breakpoints: {
        576: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 1, spaceBetween: 24 },
        992: { slidesPerView: 1, spaceBetween: 28 },
      },
      pagination: { el: pagEl, clickable: true },
      ...(hasNavigation ? { navigation: { nextEl, prevEl } } : {}),
    });
  });
}

function initProductSliders(Swiper, modules) {
  // Initialize thumbs if present
  document.querySelectorAll('.product-gallery').forEach(container => {
    const mainEl = container.querySelector('.swiper[data-swiper="product"]');
    const thumbsEl = container.querySelector('.swiper[data-swiper="product-thumbs"]');

    let thumbsInstance = null;
    if (thumbsEl) {
      thumbsInstance = new Swiper(thumbsEl, {
        modules: [modules.Navigation],
        slidesPerView: 5,
        spaceBetween: 8,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
          0: { slidesPerView: 4 },
          576: { slidesPerView: 5 },
        },
      });
    }

    if (mainEl) {
      const pagEl = mainEl.querySelector('.swiper-pagination');
      new Swiper(mainEl, {
        modules: [modules.Pagination, modules.EffectFade, modules.Thumbs],
        loop: true,
        speed: 500,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        slidesPerView: 1,
        spaceBetween: 8,
        pagination: { el: pagEl, clickable: true },
        thumbs: thumbsInstance ? { swiper: thumbsInstance } : undefined,
      });
    }
  });
}

function initSwipers() {
  const anySwiper = document.querySelector('.swiper');
  if (!anySwiper) return; // No sliders on this page
  loadSwiper().then(({ Swiper, modules }) => {
    initCategorySliders(Swiper, modules);
    initProductSliders(Swiper, modules);
  }).catch(() => {
    // ignore
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initSwipers);
} else {
  initSwipers();
}
