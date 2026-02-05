<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigTitleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $editUrl = route('config_titles.edit', $this->id);
        $deleteUrl = route('config_titles.destroy', $this->id);

        $viewLabel = __('translation.general.view');
        $editLabel = __('translation.general.edit');
        $deleteLabel = __('translation.general.delete');
        
        return [
            'id' => $this->id,
            'key' => $this->key,
            'page' => $this->page,
            'title' => $this->title,
            'description' => $this->description,
            'translations' => $this->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'title' => $translation->title,
                    'description' => $translation->description,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'action' => '
                <div class="dropdown text-muted">
                    <a href="javascript:void(0)" class="dropdown-toggle drop-arrow-none fs-xxl link-reset p-0" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item view-btn">
                                <i class="bi bi-eye me-2"></i> ' . e($viewLabel) . '
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item edit-btn">
                                <i class="bi bi-pencil-square me-2"></i> ' . e($editLabel) . '
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item text-danger delete-btn" data-id="' . $this->id . '">
                                <i class="bi bi-trash me-2"></i> ' . e($deleteLabel) . '
                            </a>
                        </li>
                    </ul>
                </div>'
        ];
    }
}
