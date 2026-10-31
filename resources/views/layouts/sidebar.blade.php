<!-- Desktop Sidebar (lg+ only) -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-button:single-button {
        background-color: transparent;
        display: block;
        border-style: solid;
        height: 10px;
        width: 10px;
    }
    /* Up Arrow */
    .custom-scrollbar::-webkit-scrollbar-button:single-button:vertical:decrement {
        border-width: 0 4px 4px 4px;
        border-color: transparent transparent #f97316 transparent;
    }
    /* Down Arrow */
    .custom-scrollbar::-webkit-scrollbar-button:single-button:vertical:increment {
        border-width: 4px 4px 0 4px;
        border-color: #f97316 transparent transparent transparent;
    }
    
    /* Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #f97316 transparent;
    }
</style>
<aside class="w-72 hidden lg:flex flex-col border-r border-gray-100 dark:border-white/5 bg-white dark:bg-[#0F0F0F] fixed left-0 top-20 bottom-0 overflow-y-auto transition-colors duration-500 z-[10000] custom-scrollbar"
       id="desktop-sidebar">
    @if(request()->routeIs('studio.*') || request()->routeIs('user.*'))
        @include('frontend.partials.studio_sidebar')
    @else
        @include('layouts.partials.sidebar-content')
    @endif
</aside>

<script>
    (function() {
        function normalizePath(path) {
            if (!path) return '';
            try { path = decodeURIComponent(path); } catch (e) {}
            // strip query + hash
            path = path.split('#')[0].split('?')[0];
            path = path.trim().toLowerCase();
            if (path.length > 1) path = path.replace(/\/+$/, '');
            if (!path.startsWith('/')) path = '/' + path;
            return path;
        }

        function hrefPath(href) {
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return '';
            try {
                return normalizePath(new URL(href, window.location.origin).pathname);
            } catch (e) {
                return normalizePath(href);
            }
        }

        // Pick the ONE link that truly matches the current URL.
        // Blade can render more than one `.active-menu-item` on some pages
        // (e.g. category slugs overlapping, policy/about URL variants), and the
        // old code just grabbed the last one -> sidebar jumped to Vlog instead
        // of People/Blogs/History/etc.
        function findExactActive(sidebar) {
            const actives = Array.from(sidebar.querySelectorAll('.active-menu-item'));
            if (!actives.length) return null;
            if (actives.length === 1) return actives[0];

            const currentPath = normalizePath(window.location.pathname);
            const currentUrl = normalizePath(window.location.href);

            // 1) exact href == current path
            for (const el of actives) {
                if (hrefPath(el.getAttribute('href')) === currentPath) return el;
            }
            // 2) category data slug == current /category/<slug>
            for (const el of actives) {
                const slug = (el.getAttribute('data-cat-slug') || '').trim().toLowerCase();
                if (slug && currentPath === '/category/' + slug.replace(/^\/+|\/+$/g, '')) return el;
            }
            // 3) full URL match (policy pages use id+slug URLs)
            for (const el of actives) {
                if (normalizePath(el.getAttribute('href')) === currentUrl) return el;
            }
            // 4) fallback: first active in DOM order (topmost section), never the last
            return actives[0];
        }

        function scrollDesktopSidebarToActive() {
            const sidebar = document.getElementById('desktop-sidebar');
            if (!sidebar) return;
            // hidden below lg -> no layout, nothing to scroll
            if (sidebar.offsetParent === null && getComputedStyle(sidebar).display === 'none') return;
            if (!sidebar.clientHeight) return;

            const activeLink = findExactActive(sidebar);
            if (!activeLink) return;

            // Container-relative math. offsetTop alone is wrong for deeply nested
            // links (it ignores intermediate wrappers), and scrollIntoView()
            // scrolls the whole page. rect-diff only moves the sidebar itself.
            const sideRect = sidebar.getBoundingClientRect();
            const linkRect = activeLink.getBoundingClientRect();
            if (!linkRect.height && !linkRect.width) return;

            const delta = linkRect.top - sideRect.top;
            const target = sidebar.scrollTop + delta - (sidebar.clientHeight / 2) + (linkRect.height / 2);
            const max = Math.max(0, sidebar.scrollHeight - sidebar.clientHeight);
            sidebar.scrollTo({ top: Math.min(Math.max(0, target), max), behavior: 'auto' });
        }

        // Expose for soft navigations / Alpine / Livewire style updates
        window.scrollDesktopSidebarToActive = scrollDesktopSidebarToActive;

        function scheduleScroll() {
            requestAnimationFrame(() => {
                scrollDesktopSidebarToActive();
                // re-run after fonts/images settle (layout shifts change rects)
                setTimeout(scrollDesktopSidebarToActive, 300);
                setTimeout(scrollDesktopSidebarToActive, 1000);
            });
        }

        document.addEventListener('DOMContentLoaded', scheduleScroll);
        window.addEventListener('load', scheduleScroll);
        window.addEventListener('popstate', scheduleScroll);
        window.addEventListener('hashchange', scheduleScroll);
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => setTimeout(scrollDesktopSidebarToActive, 50));
        }
    })();
</script>
