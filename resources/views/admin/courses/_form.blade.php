{{-- Shared form fields for course create/edit --}}
<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Course Title *</label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $course->title ?? '') }}"
               placeholder="e.g., Introduction to Laravel" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description *</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                  rows="4" placeholder="What will students learn in this course?">{{ old('description', $course->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ✅ IMAGE FIELD --}}
    <div class="col-12">
        <label class="form-label">Course Image</label>

        {{-- Preview box --}}
        <div id="img-preview-box"
             style="width:100%;height:200px;border-radius:12px;overflow:hidden;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;margin-bottom:10px;position:relative;">

            @if(isset($course) && $course->image)
                <img id="img-preview"
                     src="{{ asset('storage/' . $course->image) }}"
                     style="width:100%;height:100%;object-fit:cover;">
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.2);"></div>
            @else
                <div id="img-placeholder" style="text-align:center;color:rgba(255,255,255,0.85);">
                    <i class="bi bi-image" style="font-size:48px;display:block;margin-bottom:8px;"></i>
                    <span style="font-size:13px;">No image uploaded</span>
                </div>
                <img id="img-preview"
                     src=""
                     style="width:100%;height:100%;object-fit:cover;display:none;">
            @endif
        </div>

        {{-- Upload input --}}
        <div class="d-flex align-items-center gap-2">
            <label for="image-upload"
                   style="cursor:pointer;background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-upload"></i>
                {{ isset($course) && $course->image ? 'Change Image' : 'Upload Image' }}
            </label>
            <input type="file"
                   name="image"
                   id="image-upload"
                   accept="image/*"
                   class="d-none"
                   onchange="previewCourseImage(this)">
            <span id="file-name" style="font-size:13px;color:#64748b;">
                {{ isset($course) && $course->image ? basename($course->image) : 'No file chosen' }}
            </span>
        </div>

        {{-- Delete existing image checkbox --}}
        @if(isset($course) && $course->image)
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
            <label class="form-check-label text-danger" for="remove_image" style="font-size:13px;">
                <i class="bi bi-trash me-1"></i> Remove current image
            </label>
        </div>
        @endif

        <div class="form-text">Recommended: 1280x720px (16:9). Max 2MB. JPG, PNG, WEBP.</div>
        @error('image')<div class="text-danger mt-1" style="font-size:13px;">{{ $message }}</div>@enderror
    </div>
    {{-- ✅ END IMAGE FIELD --}}

    @if(isset($professors))
    <div class="col-sm-6">
        <label class="form-label">Professor *</label>
        <select name="professor_id" class="form-select @error('professor_id') is-invalid @enderror" required>
            <option value="">— Select Professor —</option>
            @foreach($professors as $prof)
                <option value="{{ $prof->id }}"
                    {{ old('professor_id', $course->professor_id ?? '') == $prof->id ? 'selected' : '' }}>
                    {{ $prof->name }}
                </option>
            @endforeach
        </select>
        @error('professor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    <div class="col-sm-6">
        <label class="form-label">Category *</label>
        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
               value="{{ old('category', $course->category ?? '') }}"
               placeholder="e.g., Web Development, Mathematics" required>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-4">
        <label class="form-label">Level *</label>
        <select name="level" class="form-select @error('level') is-invalid @enderror" required>
            @foreach(['beginner','intermediate','advanced'] as $lvl)
                <option value="{{ $lvl }}" {{ old('level', $course->level ?? 'beginner') === $lvl ? 'selected' : '' }}>
                    {{ ucfirst($lvl) }}
                </option>
            @endforeach
        </select>
        @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6">
        <label class="form-label">Start Date</label>
        <input type="date"
            name="start_date"
            class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date', isset($course->start_date) ? $course->start_date->format('Y-m-d') : '') }}">
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label">End Date</label>
        <input type="date"
            name="end_date"
            class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date', isset($course->end_date) ? $course->end_date->format('Y-m-d') : '') }}">
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!--div class="col-sm-4">
        <label class="form-label">Duration (hours) *</label>
        <input type="number" name="duration_hours" class="form-control @error('duration_hours') is-invalid @enderror"
               value="{{ old('duration_hours', $course->duration_hours ?? 1) }}" min="1" required>
        @error('duration_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div-->

    <div class="col-sm-4">
        <label class="form-label">Price ($) *</label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', $course->price ?? 0) }}" min="0" step="0.01" required>
        <div class="form-text">Enter 0 for a free course.</div>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                   {{ old('is_published', $course->is_published ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_published">
                Publish immediately
            </label>
            <div class="form-text">Unpublished courses are hidden from students.</div>
        </div>
    </div>
</div>

{{-- Preview Script --}}
<script>
function previewCourseImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Update file name
        document.getElementById('file-name').textContent = file.name;

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('img-preview');
            const placeholder = document.getElementById('img-placeholder');

            preview.src = e.target.result;
            preview.style.display = 'block';

            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>