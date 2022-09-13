 <?php $__env->startSection('content'); ?>
    <?php if(session()->has('create_message')): ?>
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->get('create_message')); ?></div>
    <?php endif; ?>
    <?php if(session()->has('edit_message')): ?>
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->get('edit_message')); ?></div>
    <?php endif; ?>
    <?php if(session()->has('import_message')): ?>
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->get('import_message')); ?></div>
    <?php endif; ?>
    <?php if(session()->has('not_permitted')): ?>
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
    <?php endif; ?>
    <?php if(session()->has('message')): ?>
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div>
    <?php endif; ?>

    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class=""><?php echo e(trans('file.Sections')); ?></h3>
                </div>
            </div>
            <div class="card col-md-10" >
                <div class="card-body">
                    <div class="form">
                        <div class="form-row">
                                <div class="col-md-6">
                                    <label for="Assembly_group_node_id"><b>Group Node Id</b></label>
                                    <input type="text" class="form-control"  value="<?php echo e($assemblyGroupNode->assemblyGroupNodeId); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="Assembly_group_node_name"><b>Assembly Group Node Name</b></label>
                                    <input type="text" class="form-control"  value="<?php echo e($assemblyGroupNode->assemblyGroupName); ?>" readonly>
                                </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="request_linking_target_id"><b>Request Linking Target Id</b></label>
                                <input type="text" class="form-control"  value="<?php echo e($assemblyGroupNode->request__linkingTargetId); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="request_linking_target_type"><b>Request Linking Target Type</b></label>
                                <input type="text" class="form-control"  value="<?php echo e($assemblyGroupNode->request__linkingTargetType); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="lang"><b>Lang</b></label>
                                <input type="text" class="form-control"  value="<?php echo e($assemblyGroupNode->lang); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="short_cut_id"><b>ShotCut ID</b></label>
                                <input type="text" class="form-control"  value="<?php echo e($assemblyGroupNode->shortCutId); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row mt-5">
                            <div class="col-md-2">
                                <a href="<?php echo e(route('assembly_group_nodes.index')); ?>" class="btn btn-primary">Back</a>
                            </div>
                            
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        

    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            var table = $('#product-data-table').DataTable({
                responsive: true,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                "processing": true,
                "paging": false
                // "serverSide": true,
            });

        });
        $('select').selectpicker();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ERP\resources\views/assembly_group_node/show.blade.php ENDPATH**/ ?>