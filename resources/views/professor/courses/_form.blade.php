{{-- Shared form fields for professor course create/edit --}}
<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Course Title *</label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $course->title ?? '') }}"
               placeholder="e.g., Introduction to Python" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description *</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                  rows="4" placeholder="Describe what students will learn...">{{ old('description', $course->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label">Category *</label>
        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
               value="{{ old('category', $course->category ?? '') }}"
               placeholder="e.g., Programming, Mathematics" required>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
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

    <div class="col-sm-4">
        <label class="form-label">Price ($) *</label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', $course->price ?? 0) }}" min="0" step="0.01" required>
        <div class="form-text">0 = Free course</div>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                   {{ old('is_published', $course->is_published ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_published">Publish this course</label>
            <div class="form-text">Published courses are visible to students.</div>
        </div>
    </div>
</div>
