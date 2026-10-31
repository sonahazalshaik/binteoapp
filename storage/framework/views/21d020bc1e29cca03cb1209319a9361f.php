

<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <form action="" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            
                            <div class="form-group col-md-12">
                                <label class="form-label"><?php echo app('translator')->get('Select Upload Storage'); ?></label>
                                <select class="form-control" name="storage_type">
                                    <option <?php echo e(gs('storage_type') == 1 ? 'selected' : ''); ?> value="1"><?php echo app('translator')->get('Local Storage'); ?></option>
                                    <option <?php echo e(gs('storage_type') == 2 ? 'selected' : ''); ?> value="2"><?php echo app('translator')->get('FTP Storage'); ?></option>
                                    <option <?php echo e(gs('storage_type') == 3 ? 'selected' : ''); ?> value="3"><?php echo app('translator')->get('Wasabi Storage'); ?></option>
                                    <option <?php echo e(gs('storage_type') == 4 ? 'selected' : ''); ?> value="4"><?php echo app('translator')->get('Digital Ocean'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row config">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mt-8">
                                    <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.3em] hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 ">
                                        <?php echo app('translator')->get('Update'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            "use strict";
            $('select[name=storage_type]').on('change', function() {
                var val = $(this).val();

                if (val == 1) {
                    $('.config').children().remove();
                } else if (val == 2) {
                    var ftp = `<div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('FTP Hosting Root Access Path'); ?></label>
                                    <input class="form-control" type="text" name="ftp[host_domain]" placeholder="<?php echo app('translator')->get('https://yourdomain.com/foldername'); ?>"
                                           value="<?php echo e(@gs('ftp')->host_domain); ?>" required>
                                    <small class="text-danger"><?php echo app('translator')->get('https://yourdomain.com/foldername'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"> <?php echo app('translator')->get('Host'); ?></label>
                                    <input class="form-control" type="text" name="ftp[host]" placeholder="<?php echo app('translator')->get('Host'); ?>"
                                           value="<?php echo e(@gs('ftp')->host); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Username'); ?></label>
                                    <input class="form-control" type="text" name="ftp[username]" placeholder="<?php echo app('translator')->get('Username'); ?>"
                                           value="<?php echo e(@gs('ftp')->username); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Password'); ?></label>
                                    <input class="form-control" type="text" name="ftp[password]" placeholder="<?php echo app('translator')->get('Password'); ?>"
                                           value="<?php echo e(@gs('ftp')->password); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Port'); ?></label>
                                    <input class="form-control" type="text" name="ftp[port]" placeholder="<?php echo app('translator')->get('Port'); ?>"
                                           value="<?php echo e(@gs('ftp')->port); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Upload Root Folder'); ?></label>
                                    <input class="form-control" type="text" name="ftp[root_path]" placeholder="<?php echo app('translator')->get('/html_public/something'); ?>" value="<?php echo e(@gs('ftp')->root_path); ?>" required>
                                </div>
                            </div>`;

                    $('.config').html(ftp);
                } else if (val == 3) {
                    var wasabi = `<div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Driver'); ?></label>
                                <input class="form-control" type="text" name="wasabi[driver]" value="<?php echo e(@gs('wasabi')->driver); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"> <?php echo app('translator')->get('Key'); ?></label>
                                <input class="form-control" type="text" name="wasabi[key]" value="<?php echo e(@gs('wasabi')->key); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Secret'); ?></label>
                                <input class="form-control" type="text" name="wasabi[secret]" value="<?php echo e(@gs('wasabi')->secret); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Region'); ?></label>
                                <input class="form-control" type="text" name="wasabi[region]" value="<?php echo e(@gs('wasabi')->region); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Bucket'); ?></label>
                                <input class="form-control" type="text" name="wasabi[bucket]" value="<?php echo e(@gs('wasabi')->bucket); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Endpoint'); ?></label>
                                <input class="form-control" type="text" name="wasabi[endpoint]" value="<?php echo e(@gs('wasabi')->endpoint); ?>" required>
                            </div>
                        </div>`;
                    $('.config').html(wasabi);
                }else if(val == 4){
                    var digitalOcean = `<div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Driver'); ?></label>
                                <input class="form-control" type="text" name="digital_ocean[driver]" value="<?php echo e(@gs('digital_ocean')->driver); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"> <?php echo app('translator')->get('Key'); ?></label>
                                <input class="form-control" type="text" name="digital_ocean[key]" value="<?php echo e(@gs('digital_ocean')->key); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Secret'); ?></label>
                                <input class="form-control" type="text" name="digital_ocean[secret]" value="<?php echo e(@gs('digital_ocean')->secret); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Region'); ?></label>
                                <input class="form-control" type="text" name="digital_ocean[region]" value="<?php echo e(@gs('digital_ocean')->region); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Bucket'); ?></label>
                                <input class="form-control" type="text" name="digital_ocean[bucket]" value="<?php echo e(@gs('digital_ocean')->bucket); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="required"><?php echo app('translator')->get('Endpoint'); ?></label>
                                <input class="form-control" type="text" name="digital_ocean[endpoint]" value="<?php echo e(@gs('digital_ocean')->endpoint); ?>" required>
                            </div>
                        </div>`;
                    $('.config').html(digitalOcean);
                }
            }).change();

        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\setting\storage.blade.php ENDPATH**/ ?>