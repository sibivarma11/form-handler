<div class="fi-fo-field-wrp">
    @if($imageUrl)
        <img 
            src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDIwMCAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMTI4IiBmaWxsPSIjRjNGNEY2Ii8+Cjx0ZXh0IHg9IjEwMCIgeT0iNjQiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzlDQTNBRiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+TG9hZGluZy4uLjwvdGV4dD4KPHN2Zz4=" 
            data-src="{{ $imageUrl }}" 
            alt="Current Image" 
            class="lazy-load max-w-full h-32 object-cover rounded-lg border"
            loading="lazy"
        >
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lazyImages = document.querySelectorAll('.lazy-load');
                
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy-load');
                            observer.unobserve(img);
                        }
                    });
                });
                
                lazyImages.forEach(img => imageObserver.observe(img));
            });
        </script>
    @else
        <div class="text-gray-500">No image uploaded</div>
    @endif
</div>