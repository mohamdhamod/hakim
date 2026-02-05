<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'description',
    ];

    protected $appends = ['full_path', 'file_size_formatted'];

    /**
     * Get the examination.
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Get full path to file.
     */
    public function getFullPathAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get formatted file size.
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Check if file is an image.
     */
    public function isImage(): bool
    {
        return $this->file_type === 'image' || str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if file is a PDF.
     */
    public function isPdf(): bool
    {
        return $this->file_type === 'pdf' || $this->mime_type === 'application/pdf';
    }
}
