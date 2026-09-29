<!-- Toolbar: Export Buttons + Conditional Bulk Actions -->
<div class="mb-6 flex flex-col gap-4">
    
    <!-- View Mode Toggle + Export Row -->
    <div class="w-full pb-2 sm:pb-0">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
            <!-- View Toggle -->
            <div class="flex flex-wrap items-center gap-2 shrink-0 sm:pr-4 sm:border-r border-slate-100 dark:border-white/5 w-full sm:w-auto pb-1 sm:pb-0">
                <button @click="$store.viewMode.set('app')" :class="$store.viewMode.mode === 'app' ? 'bg-rose-500 text-white border-rose-500 shadow-lg shadow-rose-500/20' : 'bg-white dark:bg-white/5 text-slate-500 border-slate-200 dark:border-white/10'" class="h-9 px-4 rounded-full border flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap">
                    <span class="material-symbols-rounded text-sm">grid_view</span> App
                </button>
                <button @click="$store.viewMode.set('table')" :class="$store.viewMode.mode === 'table' ? 'bg-rose-500 text-white border-rose-500 shadow-lg shadow-rose-500/20' : 'bg-white dark:bg-white/5 text-slate-500 border-slate-200 dark:border-white/10'" class="h-9 px-4 rounded-full border flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap">
                    <span class="material-symbols-rounded text-sm">table_rows</span> Table
                </button>
            </div>

            <!-- Export Buttons -->
            @if(isset($module) && $module)
            @php
                $exportLabels = [
                    'xlsx' => ['label' => 'Excel', 'icon' => 'table_view', 'color' => 'emerald'],
                    'csv'  => ['label' => 'CSV',   'icon' => 'description',    'color' => 'blue'],
                    'pdf'  => ['label' => 'PDF',   'icon' => 'picture_as_pdf', 'color' => 'rose'],
                    'docx' => ['label' => 'Docx',  'icon' => 'article',        'color' => 'cyan'],
                ];
            @endphp
            @php
                $exportQuery = array_filter(array_merge(request()->query(), [
                    'scope' => $exportScope ?? null,
                    'user_id' => request()->route('user_id'),
                ]));
            @endphp
            <div class="flex flex-wrap items-center gap-2 shrink-0 w-full sm:w-auto pb-1 sm:pb-0" x-data="{}">
                @foreach($exportLabels as $fmt => $info)
                <button @click.prevent="
                    @if(isset($exportTotal) && (int) $exportTotal === 0)
                        window.adminSwal({title: 'No Entries', text: 'There are no entries to export for the current filter.', icon: 'warning'});
                    @else
                    let url = '{{ route('admin.export', ['module' => $module, 'format' => $fmt]) }}{!! $exportQuery ? '?' . http_build_query($exportQuery) : '' !!}';
                    let label = '{{ $info['label'] }}';
                    window.adminSwal({
                        title: 'Exporting ' + label,
                        text: 'Preparing your ' + label + ' file...',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            setTimeout(() => {
                                window.location.href = url;
                                setTimeout(() => {
                                    Swal.close();
                                    window.adminSwal({
                                        title: 'Export Complete',
                                        text: label + ' file has been downloaded.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }, 2000);
                            }, 500);
                        }
                    });
                    @endif
                " class="h-9 px-4 rounded-full bg-white dark:bg-white/5 border border-{{ $info['color'] }}-500/30 text-{{ $info['color'] }}-500 hover:bg-{{ $info['color'] }}-500 hover:text-white flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap">
                    <span class="material-symbols-rounded text-sm">{{ $info['icon'] }}</span> {{ $info['label'] }}
                </button>
                @endforeach
            </div>
            @else
            <div class="flex flex-wrap items-center gap-2 shrink-0 w-full sm:w-auto pb-1 sm:pb-0">
                <button class="h-9 px-4 rounded-full bg-white dark:bg-white/5 border border-emerald-500/30 text-emerald-500 hover:bg-emerald-500 hover:text-white flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap" onclick="window.adminSwal({title:'Export',text:'Export not configured for this page.',icon:'info'})">
                    <span class="material-symbols-rounded text-sm">table_view</span> Excel
                </button>
                <button class="h-9 px-4 rounded-full bg-white dark:bg-white/5 border border-blue-500/30 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap" onclick="window.adminSwal({title:'Export',text:'Export not configured for this page.',icon:'info'})">
                    <span class="material-symbols-rounded text-sm">description</span> CSV
                </button>
                <button class="h-9 px-4 rounded-full bg-white dark:bg-white/5 border border-rose-500/30 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap" onclick="window.adminSwal({title:'Export',text:'Export not configured for this page.',icon:'info'})">
                    <span class="material-symbols-rounded text-sm">picture_as_pdf</span> PDF
                </button>
                <button class="h-9 px-4 rounded-full bg-white dark:bg-white/5 border border-cyan-500/30 text-cyan-500 hover:bg-cyan-500 hover:text-white flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-all shadow-sm whitespace-nowrap" onclick="window.adminSwal({title:'Export',text:'Export not configured for this page.',icon:'info'})">
                    <span class="material-symbols-rounded text-sm">article</span> Docx
                </button>
            </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        const saved = localStorage.getItem('adminViewMode');
        Alpine.store('viewMode', {
            mode: saved || (window.innerWidth >= 1024 ? 'table' : 'app'),
            set(m) { this.mode = m; localStorage.setItem('adminViewMode', m); }
        });
    });
    </script>

    <!-- Bottom Row: Bulk Actions (Only for Users, Channels, Marketplace, Videos) -->
    @if(isset($showBulkActions) && $showBulkActions)
    <div class="w-full" x-data="bulkActions({ bulkRoute: '{{ $bulkRoute ?? '' }}' })" x-init="init()">
        <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2 block">Bulk Actions</span>
        
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <!-- Select All Checkbox Pill -->
            <label class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm cursor-pointer transition-all">
                <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                <span>Select All</span>
                <span x-show="selectedCount > 0" x-text="'(' + selectedCount + ')'" class="text-rose-500 font-black text-[10px]"></span>
            </label>


            <!-- Status Buttons -->
            @if(!isset($bulkActions) || in_array('approve', $bulkActions))
            <button @click="executeBulk('approve')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-emerald-500">check_circle</span> Approve
            </button>
            @endif
            
            @if(!isset($bulkActions) || in_array('unapprove', $bulkActions))
            <button @click="executeBulk('unapprove')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-rose-500">cancel</span> Unapprove
            </button>
            @endif
            
            @if(!isset($bulkActions) || in_array('featured', $bulkActions))
            <button @click="executeBulk('featured')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-yellow-500">star</span> Featured
            </button>
            @endif

            @if(!isset($bulkActions) || in_array('unfeatured', $bulkActions))
            <button @click="executeBulk('unfeatured')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-slate-400">star_outline</span> Unfeatured
            </button>
            @endif
            
            @if(!isset($bulkActions) || in_array('reject', $bulkActions))
            <button @click="executeBulk('reject')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-slate-900 dark:text-white">block</span> Reject
            </button>
            @endif

            @if(!isset($bulkActions) || in_array('trending', $bulkActions))
            <button @click="executeBulk('trending')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-orange-500">trending_up</span> Trending
            </button>
            @endif

            @if(!isset($bulkActions) || in_array('untrending', $bulkActions))
            <button @click="executeBulk('untrending')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/70 hover:bg-slate-50 dark:hover:bg-white/5 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-slate-400">trending_flat</span> Normal
            </button>
            @endif

            @if(!isset($bulkActions) || in_array('delete', $bulkActions))
            <button @click="executeBulk('delete')" class="h-9 px-4 rounded-full bg-white dark:bg-[#1a1a1a] border border-rose-500/20 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center gap-2 text-[11px] font-bold shadow-sm transition-all">
                <span class="material-symbols-rounded text-sm text-rose-500">delete</span> Delete
            </button>
            @endif
        </div>

        <form id="bulkActionForm" method="POST" :action="bulkRoute" style="display: none;">
            @csrf
            <input type="hidden" name="action">
            <div id="bulkIdsContainer"></div>
        </form>
    </div>
    
    <script>
    window.bulkActions = function(config = {}) {
        return {
            selectAll: false,
            selectedCount: 0,
            bulkRoute: config.bulkRoute || '',
            bulkAction: '',
            bulkIds: [],
            
            init() {
                // Watch for individual checkbox changes
                this.$watch('selectAll', () => this.updateCount());
            },
            
            toggleAll() {
                const checkboxes = document.querySelectorAll('.bulk-select-item');
                checkboxes.forEach(cb => { cb.checked = this.selectAll; });
                this.updateCount();
            },
            
            updateCount() {
                const checkboxes = document.querySelectorAll('.bulk-select-item:checked');
                this.selectedCount = checkboxes.length;
            },
            
            getSelectedIds() {
                const checkboxes = document.querySelectorAll('.bulk-select-item:checked');
                return Array.from(checkboxes).map(cb => cb.value);
            },
            
            executeBulk(action) {
                this.updateCount();
                const ids = this.getSelectedIds();
                if (ids.length === 0) {
                    window.adminSwal({
                        title: 'Selection Required',
                        text: 'Please select at least one entry to perform this action.',
                        icon: 'info'
                    });
                    return;
                }
                
                window.adminSwal({
                    title: 'Confirm Bulk Action',
                    text: 'Are you sure you want to ' + action + ' ' + ids.length + ' selected entries?',
                    icon: action === 'delete' ? 'error' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: action === 'delete' ? '#ef4444' : '#f97316',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Abort'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('bulkActionForm');
                        if (!form) {
                            window.adminSwal({ title: 'Error', text: 'Bulk action form not found.', icon: 'error' });
                            return;
                        }

                        // Set action
                        form.querySelector('input[name="action"]').value = action;
                        
                        // Clear and populate IDs container
                        const container = form.querySelector('#bulkIdsContainer');
                        container.innerHTML = '';
                        ids.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            container.appendChild(input);
                        });
                        
                        if (!this.bulkRoute) {
                            window.adminSwal({ title: 'Configuration Error', text: 'Bulk action route not defined for this page.', icon: 'error' });
                            return;
                        }

                        form.submit();
                    }
                });
            }
        };
    };
    
    // Auto-update count when individual checkboxes change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('bulk-select-item') || e.target.id === 'selectAllHeader') {
            const component = document.querySelector('[x-data*="bulkActions"]');
            if (component) {
                const data = Alpine.$data(component);
                if (data) {
                    const checkboxes = document.querySelectorAll('.bulk-select-item:checked');
                    const allCheckboxes = document.querySelectorAll('.bulk-select-item');
                    data.selectedCount = checkboxes.length;
                    data.selectAll = (checkboxes.length === allCheckboxes.length && allCheckboxes.length > 0);
                }
            }
        }
    });
    </script>
    @endif

    <!-- Top Scrollbar: Synchronized with Table View -->
    <div x-show="$store.viewMode.mode === 'table'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="hidden sm:flex items-center gap-3 mb-6 group/scroll-controls">
        
        <!-- Scroll Left Trigger -->
        <button onclick="document.getElementById('top-scrollbar-container').scrollBy({left: -400, behavior: 'smooth'})" 
                class="h-10 w-10 rounded-2xl bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20 active:scale-90 shrink-0">
            <span class="material-symbols-rounded text-xl">chevron_left</span>
        </button>

        <div id="top-scrollbar-container" 
             class="flex-grow overflow-x-auto overflow-y-hidden h-3 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 p-0.5 scrollbar-thin scrollbar-thumb-orange-500 scrollbar-track-transparent">
            <div id="top-scrollbar-content" class="h-full bg-orange-500/10 rounded-full"></div>
        </div>

        <!-- Scroll Right Trigger -->
        <button onclick="document.getElementById('top-scrollbar-container').scrollBy({left: 400, behavior: 'smooth'})" 
                class="h-10 w-10 rounded-2xl bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20 active:scale-90 shrink-0">
            <span class="material-symbols-rounded text-xl">chevron_right</span>
        </button>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const topScroll = document.getElementById('top-scrollbar-container');
        const topContent = document.getElementById('top-scrollbar-content');
        const scrollControls = document.querySelector('.group\\/scroll-controls');
        
        const findTableContainer = () => {
            return document.querySelector('.table-responsive') || 
                   document.querySelector('.overflow-x-auto table')?.parentElement;
        };

        const initSync = () => {
            const tableContainer = findTableContainer();
            if (!topScroll || !tableContainer) return;

            const updateWidth = () => {
                const table = tableContainer.querySelector('table');
                if (table) {
                    topContent.style.width = table.offsetWidth + 'px';
                    
                    if (table.offsetWidth <= tableContainer.offsetWidth) {
                        scrollControls.style.display = 'none';
                    } else {
                        scrollControls.style.display = 'flex';
                    }
                }
            };

            topScroll.onscroll = () => {
                if (tableContainer.scrollLeft !== topScroll.scrollLeft) {
                    tableContainer.scrollLeft = topScroll.scrollLeft;
                }
            };
            
            tableContainer.onscroll = () => {
                if (topScroll.scrollLeft !== tableContainer.scrollLeft) {
                    topScroll.scrollLeft = tableContainer.scrollLeft;
                }
            };

            window.addEventListener('resize', updateWidth);
            setTimeout(updateWidth, 500);
            
            const observer = new MutationObserver(updateWidth);
            observer.observe(tableContainer, { childList: true, subtree: true, characterData: true });
        };

        setTimeout(initSync, 1000);
    });
    </script>
</div>
