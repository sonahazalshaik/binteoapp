const fs = require('fs');
const file = 'c:/laragon/www/new-youtube/resources/views/frontend/videos/create.blade.php';
let content = fs.readFileSync(file, 'utf8');

const startIdx = content.indexOf('            async startUpload(isDraft = false) {');
const endIdx = content.indexOf('            async startBackgroundUpload() {');

if (startIdx !== -1 && endIdx !== -1) {
    const newStartUpload = `            async startUpload(isDraft = false) {
                if (this.uploading || this.backgroundUploading || this.bunnyStatus !== 'ready') return;
                
                this.uploading = true;
                
                try {
                    // Force an auto-save first to ensure latest metadata is stored
                    await new Promise(resolve => this.autoSaveDraft(true, false, resolve));
                    
                    const formData = new FormData();
                    if (this.title) formData.append('title', this.title);
                    if (this.draftId) formData.append('draft_id', this.draftId);
                    const catVal = this.categoryId || (document.querySelector('select[name="category_id"]') ? document.querySelector('select[name="category_id"]').value : '') || '';
                    if (catVal) formData.append('category_id', catVal);
                    formData.append('description', this.description);
                    formData.append('visibility', this.visibility);
                    formData.append('language', this.language || '');
                    formData.append('location', this.location);
                    formData.append('duration', this.duration);
                    formData.append('pricing_tier', this.pricingTier);
                    formData.append('price', this.price);
                    formData.append('is_age_restricted', this.isAgeRestricted ? 1 : 0);
                    formData.append('schedule_video', this.scheduleVideo ? 1 : 0);
                    formData.append('schedule_date', this.scheduleDate);
                    formData.append('schedule_time', this.scheduleTime);
                    formData.append('tags', JSON.stringify(this.tags));
                    if (isDraft) formData.append('is_draft', '1');
                    
                    const prepRes = await fetch("{{ route('videos.prepare_upload') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!prepRes.ok) {
                        const errText = await prepRes.text().catch(() => '');
                        throw new Error(\`Publish failed (HTTP \${prepRes.status}): \${errText.slice(0, 150)}\`);
                    }

                    const prepData = await prepRes.json();
                    if(!prepData.success) {
                        throw new Error(prepData.message || 'Failed to publish video');
                    }

                    const thumbInput = document.querySelector('input[name="thumbnail"]');
                    if (thumbInput && thumbInput.files[0]) {
                        try {
                            const swalText = document.querySelector('.swal2-html-container p');
                            if (swalText) swalText.innerText = 'Uploading thumbnail...';

                            const thumbData = new FormData();
                            thumbData.append('thumbnail', thumbInput.files[0]);
                            await fetch(\`/videos/\${prepData.video_slug}/thumbnail\`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: thumbData
                            });
                        } catch (thumbErr) {
                            console.error('Thumbnail upload error:', thumbErr);
                        }
                    }

                    this.uploading = false;
                    if (window.localforage && this.draftId) {
                        localforage.removeItem('draft_file_' + this.draftId);
                    }
                    notify('success', 'Video published successfully');
                    window.location.href = "{{ route('studio.dashboard') }}";
                    
                } catch (err) {
                    this.uploading = false;
                    if(window.Swal) Swal.close();
                    notify('error', err.message || 'An error occurred during publish');
                    console.error('Upload Error:', err);
                }
            },

`;
    
    content = content.substring(0, startIdx) + newStartUpload + content.substring(endIdx);
    fs.writeFileSync(file, content);
    console.log("Successfully replaced startUpload");
} else {
    console.log("Could not find startUpload or startBackgroundUpload boundaries");
}
