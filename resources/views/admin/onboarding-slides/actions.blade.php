<div class="flex items-center gap-2">
    <a href="{{ route('admin.onboarding-slides.show', $row->id) }}"
       class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-all text-sm">
        <span class="material-symbols-rounded text-lg">visibility</span>
    </a>
    <a href="{{ route('admin.onboarding-slides.edit', $row->id) }}"
       class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all text-sm">
        <span class="material-symbols-rounded text-lg">edit</span>
    </a>
    <form action="{{ route('admin.onboarding-slides.destroy', $row->id) }}" method="POST"
          data-swal-question="Are you sure you want to delete this slide?" class="inline">
        @csrf @method('DELETE')
        <button type="submit"
                class="w-9 h-9 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all text-sm">
            <span class="material-symbols-rounded text-lg">delete</span>
        </button>
    </form>
</div>
