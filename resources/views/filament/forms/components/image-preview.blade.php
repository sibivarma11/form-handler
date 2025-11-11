<div class="fi-fo-field-wrp">
    @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="Current Image" class="max-w-full h-32 object-cover rounded-lg border">
    @else
        <div class="text-gray-500">No image uploaded</div>
    @endif
</div>