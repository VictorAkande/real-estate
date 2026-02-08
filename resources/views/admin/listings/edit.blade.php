@extends('layouts.admin', ['heading' => 'Edit Listing'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.listings.update', $listing) }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-md-8">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" value="{{ old('title', $listing->title) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Listing Type</label>
            <select class="form-select" name="listing_type" required>
                @foreach (['sale' => 'Sale', 'rent' => 'Rent', 'shortlet' => 'Short Let'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('listing_type', $listing->listing_type) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Property Type</label>
            <input class="form-control" name="property_type" value="{{ old('property_type', $listing->property_type) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Price</label>
            <input class="form-control" name="price" value="{{ old('price', $listing->price) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <input class="form-control" name="status" value="{{ old('status', $listing->status) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Bedrooms</label>
            <input class="form-control" name="bedrooms" value="{{ old('bedrooms', $listing->bedrooms) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Bathrooms</label>
            <input class="form-control" name="bathrooms" value="{{ old('bathrooms', $listing->bathrooms) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Toilets</label>
            <input class="form-control" name="toilets" value="{{ old('toilets', $listing->toilets) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Parking</label>
            <input class="form-control" name="parking_spaces" value="{{ old('parking_spaces', $listing->parking_spaces) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Area (sqm)</label>
            <input class="form-control" name="area_sqm" value="{{ old('area_sqm', $listing->area_sqm) }}">
        </div>
        <div class="col-md-5">
            <label class="form-label">Address</label>
            <input class="form-control" name="address" value="{{ old('address', $listing->address) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Location</label>
            <select class="form-select" name="location_id" required>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('location_id', $listing->location_id) == $location->id)>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Agent/Developer</label>
            <select class="form-select" name="agent_id">
                <option value="">Unassigned</option>
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}" @selected(old('agent_id', $listing->agent_id) == $agent->id)>{{ $agent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Cover Image URL</label>
            <input class="form-control" name="cover_image" value="{{ old('cover_image', $listing->cover_image) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Cover Image Upload</label>
            <input class="form-control" type="file" name="cover_image_file" accept="image/*">
            @if ($listing->cover_image)
                <div class="small text-muted mt-1">Current: {{ $listing->cover_image }}</div>
            @endif
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" @checked(old('featured', $listing->featured))>
                <label class="form-check-label" for="featured">Featured</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4">{{ old('description', $listing->description) }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Update listing</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.listings.index') }}">Cancel</a>
        </div>
    </form>

    <hr class="my-4">

    <div class="row g-3">
        <div class="col-lg-6">
            <h5 class="fw-bold mb-3">Gallery images</h5>
            <form method="POST" action="{{ route('admin.listings.gallery.upload', $listing) }}" enctype="multipart/form-data" class="d-grid gap-2">
                @csrf
                <input class="form-control" type="file" name="gallery_images[]" multiple accept="image/*">
                <button class="btn btn-outline-primary" type="submit">Upload images</button>
            </form>
        </div>
        <div class="col-lg-6">
            <h6 class="fw-bold">Reorder gallery</h6>
            <p class="text-muted small">Drag and drop images to change their order.</p>
            <form method="POST" action="{{ route('admin.listings.gallery.order', $listing) }}" id="galleryOrderForm">
                @csrf
                <input type="hidden" name="order" id="galleryOrderInput" value="">
                <div class="gallery-grid" id="galleryGrid">
                    @foreach ($gallery as $image)
                        <div class="gallery-item" draggable="true" data-id="{{ $image->id }}">
                            <img src="{{ Storage::url($image->thumb_path ?? $image->image_path) }}" alt="Gallery image">
                            <button class="btn btn-sm btn-outline-danger mt-2" type="button" data-delete-url="{{ route('admin.listings.gallery.delete', [$listing, $image]) }}">Delete</button>
                        </div>
                    @endforeach
                </div>
                <button class="btn btn-primary mt-3" type="submit">Save order</button>
            </form>
            <form method="POST" id="galleryDeleteForm" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
    const grid = document.getElementById('galleryGrid');
    const orderInput = document.getElementById('galleryOrderInput');

    const deleteForm = document.getElementById('galleryDeleteForm');

    if (grid) {
        let dragged = null;

        grid.querySelectorAll('.gallery-item').forEach((item) => {
            item.addEventListener('dragstart', () => {
                dragged = item;
                item.classList.add('dragging');
            });
            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
                dragged = null;
                updateOrder();
            });
        });

        grid.addEventListener('dragover', (event) => {
            event.preventDefault();
            const after = getDragAfterElement(grid, event.clientY);
            if (!dragged) {
                return;
            }
            if (after == null) {
                grid.appendChild(dragged);
            } else {
                grid.insertBefore(dragged, after);
            }
        });

        const getDragAfterElement = (container, y) => {
            const elements = [...container.querySelectorAll('.gallery-item:not(.dragging)')];
            return elements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset, element: child };
                }
                return closest;
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        };

        const updateOrder = () => {
            const ids = [...grid.querySelectorAll('.gallery-item')].map((item) => item.dataset.id);
            orderInput.value = ids.join(',');
        };

        updateOrder();

        grid.addEventListener('click', (event) => {
            const button = event.target.closest('[data-delete-url]');
            if (!button) {
                return;
            }
            const url = button.getAttribute('data-delete-url');
            if (deleteForm && url && confirm('Delete this gallery image?')) {
                deleteForm.action = url;
                deleteForm.submit();
            }
        });
    }
</script>
@endsection
