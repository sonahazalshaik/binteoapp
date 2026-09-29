const fs = require('fs');
const file = 'resources/views/frontend/videos/show.blade.php';
let content = fs.readFileSync(file, 'utf8');
let lines = content.split('\n');

const toInsert = `                                                        <div class="flex gap-3 w-full">
                                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 uppercase overflow-hidden border border-gray-100 dark:border-white/10">
                                                            @if(auth()->user()->channel?->avatar)
                                                                <img src="{{ getImage(getFilePath('channelAvatar') . '/' . auth()->user()->channel->avatar) }}" class="w-full h-full object-cover">
                                                            @elseif(auth()->user()->image)
                                                                <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image) }}" class="w-full h-full object-cover">
                                                            @else
                                                                <span class="text-[10px] font-black text-gray-500 dark:text-white">{{ substr(auth()->user()->channel?->name ?? auth()->user()->name, 0, 1) }}</span>
                                                            @endif
                                                        </div>
                                                         <div class="relative w-full" x-data="{ mainEmojisOpen: false }">
                                                             <div class="relative">
                                                                 <textarea 
                                                                     x-ref="commentText"
                                                                     x-model="mainComment"
                                                                     rows="1"
                                                                     @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; handleInput($event)"
                                                                     @keydown.down.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex + 1) % mentionSuggestions.length"
                                                                     @keydown.up.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex - 1 + mentionSuggestions.length) % mentionSuggestions.length"
                                                                     @keydown.enter.prevent="if(showSuggestions) { insertMention(mentionSuggestions[suggestionIndex].username); } else { $el.blur(); }"
                                                                     @keydown.escape="showSuggestions = false"
                                                                     class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 focus:ring-0 px-0 py-1 pr-8 text-[13px] text-gray-900 dark:text-white resize-none transition-all duration-300" 
                                                                     placeholder="Add a comment..."></textarea>
                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen" class="absolute right-1 top-0.5 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors" title="Add emoji">
                                                                     <span class="material-symbols-rounded text-[18px] text-gray-400">emoji_emotions</span>
                                                                 </button>
                                                             </div>
                                                             <div x-show="mainEmojisOpen" x-transition @click.away="mainEmojisOpen = false" class="absolute bottom-full mb-2 right-0 w-[280px] bg-white dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-[100] overflow-hidden">`;

lines.splice(2166, 2371 - 2167 + 1, toInsert);
fs.writeFileSync(file, lines.join('\n'));
console.log('done');
