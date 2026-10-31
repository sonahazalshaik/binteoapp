<div class="modal fade" id="formGenerateModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo app('translator')->get('Generate Form'); ?></h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <i class="las la-times"></i>
          </button>
        </div>
        <form class="<?php echo e($formClassName ?? 'generate-form'); ?>">
            <?php echo csrf_field(); ?>
              <div class="modal-body">
                <input type="hidden" name="update_id" value="">
                <div class="form-group">
                    <label><?php echo app('translator')->get('Type'); ?></label>
                    <select name="form_type" class="form-control select2" data-minimum-results-for-search="-1" required>
                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                        <option value="text"><?php echo app('translator')->get('Text'); ?></option>
                        <option value="email"><?php echo app('translator')->get('Email'); ?></option>
                        <option value="number"><?php echo app('translator')->get('Number'); ?></option>
                        <option value="url"><?php echo app('translator')->get('URL'); ?></option>
                        <option value="datetime"><?php echo app('translator')->get('Date & Time'); ?></option>
                        <option value="date"><?php echo app('translator')->get('Date'); ?></option>
                        <option value="time"><?php echo app('translator')->get('Time'); ?></option>
                        <option value="textarea"><?php echo app('translator')->get('Textarea'); ?></option>
                        <option value="select"><?php echo app('translator')->get('Select'); ?></option>
                        <option value="checkbox"><?php echo app('translator')->get('Checkbox'); ?></option>
                        <option value="radio"><?php echo app('translator')->get('Radio'); ?></option>
                        <option value="file"><?php echo app('translator')->get('File'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo app('translator')->get('Is Required'); ?></label>
                    <select name="is_required" class="form-control select2" data-minimum-results-for-search="-1" required>
                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                        <option value="required"><?php echo app('translator')->get('Required'); ?></option>
                        <option value="optional"><?php echo app('translator')->get('Optional'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo app('translator')->get('Label'); ?></label>
                    <input type="text" name="form_label" class="form-control" required>
                </div>
                <div class="form-group extra_area">

                </div>
                <div class="form-group">
                    <label><?php echo app('translator')->get('Width'); ?></label>
                    <select name="form_width" class="form-control select2" data-minimum-results-for-search="-1" required>
                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                        <option value="12"><?php echo app('translator')->get('100%'); ?></option>
                        <option value="6"><?php echo app('translator')->get('50%'); ?></option>
                        <option value="4"><?php echo app('translator')->get('33%'); ?></option>
                        <option value="3"><?php echo app('translator')->get('25%'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo app('translator')->get('Instruction'); ?> <small><?php echo app('translator')->get('(if any)'); ?></small></label>
                    <input type="text" name="instruction" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn--primary w-100 h-45 generatorSubmit"><?php echo app('translator')->get('Add'); ?></button>
              </div>
          </form>
      </div>
    </div>
</div>


<?php $__env->startPush('script-lib'); ?>
<script src="<?php echo e(asset('assets/global/js/form_generator.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\form-generator-modal.blade.php ENDPATH**/ ?>