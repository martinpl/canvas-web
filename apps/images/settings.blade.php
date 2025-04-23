<?php

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    #[Validate(['images.*.file' => 'file|mimes:jpg,jpeg,png'])]
    public $images;

    public $app;

    public function mount()
    {
        $this->setImages();
    }

    private function setImages()
    {
        $this->images = array_map(fn ($item) => ['url' => $item], $this->app->metadata()['list'] ?? []);
    }

    public function add()
    {
        $this->images[] = [];
    }

    public function remove($index)
    {
        unset($this->images[$index]);
    }

    public function save()
    {
        $this->validate();
        $list = [];
        foreach ($this->images as $currentImage) {
            if ($currentImage['url'] ?? false) {
                $list[] = $currentImage['url'];
            }

            if ($currentImage['file'] ?? false) {
                $list[] = $currentImage['file']->store('images');
            }
        }

        // Remove files that are no longer in the list
        foreach ($this->app->metadata()['list'] ?? [] as $previousImage) {
            if (str_starts_with($previousImage, 'images/') && ! in_array($previousImage, $list)) {
                Storage::delete($previousImage);
            }
        }

        $this->app->save([
            'list' => $list,
        ]);
        $this->setImages();
    }

    public function link($url)
    {
        if (str_starts_with($url, 'images/')) {
            return url('private/'.$url);
        }

        return $url;
    }
}; ?>

<div>
   @foreach($images as $index => $image)
      <div class="flex items-end gap-4">
         <flux:avatar src="{{ $this->link($image['url'] ?? '') }}" size="xl" />
         <flux:input wire:model="images.{{ $index }}.url" label="Url" value="{{ $image['url'] ?? '' }}" />
         <flux:input wire:model="images.{{ $index }}.file" label="File" type="file" />
         <flux:button wire:click="remove('{{ $index }}')">Remove</flux:button>
      </div>
   @endforeach
   <flux:button wire:click="add">
      Add
   </flux:button>
   <flux:button wire:click="save">
      Save
   </flux:button>
</div>
