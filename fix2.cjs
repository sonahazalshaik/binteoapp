const fs = require('fs');
let content = fs.readFileSync('resources/views/frontend/videos/show.blade.php', 'utf8');

// 1. Fix the textarea
if (!content.includes('<textarea') && content.includes('<div class="relative">\r\n                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen"')) {
    const textareaCode = `                                                                 <textarea 
                                                                     x-ref="commentText"
                                                                     x-model="mainComment"
                                                                     rows="1"
                                                                     @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; handleInput($event)"
                                                                     @keydown.down.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex + 1) % mentionSuggestions.length"
                                                                     @keydown.up.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex - 1 + mentionSuggestions.length) % mentionSuggestions.length"
                                                                     @keydown.enter.prevent="if(showSuggestions) { insertMention(mentionSuggestions[suggestionIndex].username); } else { $el.blur(); }"
                                                                     @keydown.escape="showSuggestions = false"
                                                                     class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 focus:ring-0 px-0 py-1 pr-8 text-[13px] text-gray-900 dark:text-white resize-none transition-all duration-300" 
                                                                     placeholder="Add a comment..."></textarea>\n`;
    content = content.replace('<div class="relative">\r\n                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen"', '<div class="relative">\r\n' + textareaCode + '                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen"');
}
if (!content.includes('<textarea') && content.includes('<div class="relative">\n                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen"')) {
    const textareaCode = `                                                                 <textarea 
                                                                     x-ref="commentText"
                                                                     x-model="mainComment"
                                                                     rows="1"
                                                                     @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; handleInput($event)"
                                                                     @keydown.down.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex + 1) % mentionSuggestions.length"
                                                                     @keydown.up.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex - 1 + mentionSuggestions.length) % mentionSuggestions.length"
                                                                     @keydown.enter.prevent="if(showSuggestions) { insertMention(mentionSuggestions[suggestionIndex].username); } else { $el.blur(); }"
                                                                     @keydown.escape="showSuggestions = false"
                                                                     class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 focus:ring-0 px-0 py-1 pr-8 text-[13px] text-gray-900 dark:text-white resize-none transition-all duration-300" 
                                                                     placeholder="Add a comment..."></textarea>\n`;
    content = content.replace('<div class="relative">\n                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen"', '<div class="relative">\n' + textareaCode + '                                                                 <button type="button" @click="mainEmojisOpen = !mainEmojisOpen"');
}

// 2. Fix the missing UI divs
const searchStringWin = `                                            </button>\r
                                       </div>\r
                                       \r
                                       <div class="flex-1 overflow-y-auto px-4 py-4 custom-scrollbar">\r
                                            <div class="space-y-6">\r
                                                 @auth\r
                                                     <div class="flex flex-col gap-2 mb-6 sticky top-0 bg-white dark:bg-[#0F0F0F] z-10 py-2 border-b border-gray-100 dark:border-white/5">\r
                                                         <div class="flex gap-3 w-full">`;
if (content.includes(searchStringWin)) {
    // Already fixed? No wait, my replace_file_content failed to apply the fix for the UI divs!
    // Oh, the replace_file_content at 06:54 DID run but only modified the textarea!
}

// Actually, wait! The user's code DID NOT HAVE the missing divs. The `replace_file_content` failed to match the TargetContent for the divs!
// Why did it fail? Let me just forcefully put the divs in!
content = content.replace(/<\/button>\s*@auth\s*<div class="flex gap-3 w-full">/g, 
`                                            </button>
                                       </div>
                                       
                                       <div class="flex-1 overflow-y-auto px-4 py-4 custom-scrollbar">
                                            <div class="space-y-6">
                                                 @auth
                                                     <div class="flex flex-col gap-2 mb-6 sticky top-0 bg-white dark:bg-[#0F0F0F] z-10 py-2 border-b border-gray-100 dark:border-white/5">
                                                         <div class="flex gap-3 w-full">`);

fs.writeFileSync('resources/views/frontend/videos/show.blade.php', content);
console.log('Fixed file.');
