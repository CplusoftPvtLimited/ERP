 <?php $__env->startSection('content'); ?>
<?php if(session()->has('create_message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('create_message')); ?></div>
<?php endif; ?>
<?php if(session()->has('edit_message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('edit_message')); ?></div>
<?php endif; ?>
<?php if(session()->has('import_message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('import_message')); ?></div>
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
<?php endif; ?>
<?php if(session()->has('message')): ?>
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div>
<?php endif; ?>

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class=""><?php echo e(trans('file.Sections')); ?></h3>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="product-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                
                    <th>#</th>
                    <th>Assembly Group Node Name</th>
                    <th>Assembly Group Node Id</th>
                    <th>Request Linking Target Id</th>
                    <th>Request Linking Target Type</th>
                    <th>Action</th>

                
                </tr>
            </thead>
            <tbody>
                
                <?php $__currentLoopData = $assemblyGroupNodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($item->assemblyGroupName); ?></td>
                        <td><?php echo e($item->assemblyGroupNodeId); ?></td>
                        <td><?php echo e($item->request__linkingTargetId); ?></td>
                        <td><?php echo e($item->request__linkingTargetType); ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                </button>
                                <div class="dropdown-menu">
                                  <a href="<?php echo e(route('assembly_group_nodes.show',$item->id)); ?>" class="dropdown-item">View</a>
                                  <a href="<?php echo e(route('get_section.parts',$item->id)); ?>" class="dropdown-item">Get Parts</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
        
        <div class="pull-right">
            <?php echo e($assemblyGroupNodes->links()); ?>

        </div>
    </div>

</section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>

    $(document).ready(function() {
        var table = $('#product-data-table').DataTable( {
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            "processing": true,
            "paging" : false
            // "serverSide": true,
        } );

    } );
    $('select').selectpicker();

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ERP\resources\views/assembly_group_node/index.blade.php ENDPATH**/ ?>