<?php
$download_url_query = '';
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
    $download_url_query = '&' . $_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title"><?= $title ?></h4>
            </div>
            <?php
            if (getUserCan('admin_users_module', 'access_create')) {
            ?>
                <div class="col-sm-8 col-md-8 mb-1 text-right">
                    <a href="<?= base_url('admin/admin_users_list?do=download-excel' . $download_url_query) ?>" class="btn btn-dark btn-rounded"><i class="fa fa-download"></i> Download data in excel</a>
                    <a href="#" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#add_user_admin" onClick="javascript:$('form#add-user-admin')[0].reset();var validator = $( 'form#add-user-admin' ).validate();validator.resetForm();$('form#add-user-admin select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Admin User</a>
                </div>
            <?php } ?>
        </div>
        <?php
        $form_attribute = array(
            'name' => 'search-user',
            'class' => '',
            'method' => "get",
            'autocomplete' => "off",
            'id' => 'search-user',
            'novalidate' => 'novalidate',
        );
        $hidden = array('action' => 'search-user');
        // Form Open
        echo form_open('admin/admin_users_list', $form_attribute, $hidden);
        ?>
        <div class="row filter-row">

            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="form-group form-focus">
                    <label class="focus-label">User Name</label>
                    <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?= (isset($searchuserKeyword) && !empty($searchuserKeyword)) ? $searchuserKeyword : ''; ?>">
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="form-group form-focus">
                    <label class="focus-label">From</label>
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker" type="text" name="date_from" id="date_from" value="<?= (isset($searchuserFromKeyword) && !empty($searchuserFromKeyword)) ? dateFormat("d-m-Y", $searchuserFromKeyword) : ''; ?>">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="form-group form-focus">
                    <label class="focus-label">To</label>
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker" type="text" name="date_to" id="date_to" value="<?= (isset($searchuserToKeyword) && !empty($searchuserToKeyword)) ? dateFormat("d-m-Y", $searchuserToKeyword) : ''; ?>">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="form-group form-focus select-focus">
                    <label class="focus-label">Status</label>
                    <select class="select floating" name="status" id="status">
                        <option value="">--Select--</option>
                        <option value="1" <?= (isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword == 1) ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?= (isset($statusKeyword) && $statusKeyword != '' && $statusKeyword == 0) ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Search</button>
                <button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?= base_url('admin/admin_users_list') ?>';">Clear</button>
            </div>
        </div>
        <?php
        // Form Close
        echo form_close(); ?>
        <?php if ($this->session->flashdata('user_admin_success')) { ?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Success!</strong> <?php echo $this->session->flashdata('user_admin_success'); ?>
            </div>

        <?php } else if ($this->session->flashdata('user_admin_error')) {  ?>
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Error!</strong> <?php echo $this->session->flashdata('user_admin_error'); ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>User Name</th>
                                <th>Email Id</th>
                                <th>Mobile No</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //echo "<pre>";print_r($adminList);
                            $srno = 1;
                            $count = 0;
                            foreach ($adminList as $adminList) {
                                $count++;
                                $class = ($count % 2 == 1) ? " odd" : " even";
                                //$productImage=getProductImage($adminList->id,$limit=1);
                                //echo "<pre>";print_r($productImage);
                            ?>
                                <tr role="row" class="<?= $class ?>">
                                    <td><?= $srno ?></td>
                                    <td><a href="#"><?= $adminList->screen_name ?></a></td>
                                    <td><?= $adminList->user_mail ?></td>
                                    <td><?= $adminList->user_phone_no ?></td>
                                    <td><?= dateFormat('d-m-Y', $adminList->date_added) ?></td>
                                    <td>
                                        <div class="dropdown action-label">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

                                                <?= (isset($adminList->user_active) && $adminList->user_active == 1) ? '<i class="fa fa-dot-circle-o text-success"></i> Active' : '<i class="fa fa-dot-circle-o text-danger"></i> Inactive'; ?>
                                            </a>
                                            <?php
                                                if (getUserCan('admin_users_module', 'access_write')) {
                                                ?>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="<?= base_url('admin/admin_user_status?do=active&user_id=' . $adminList->user_id) ?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                <a class="dropdown-item" href="<?= base_url('admin/admin_user_status?do=inactive&user_id=' . $adminList->user_id) ?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                    <?php
										if (getUserCan('admin_users_module', 'access_write') || getUserCan('admin_users_module', 'access_delete')) {
										?>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <?php
                                                if (getUserCan('admin_users_module', 'access_write')) {
                                                ?>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_user_admin" onClick="getEditData(<?= $adminList->user_id ?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <!--<a class="dropdown-item" href="<? //=base_url('admin/orders_list?do=user_order&user_id='.$adminList->user_id)
                                                                                        ?>"><i class="fa fa-address-card m-r-5"></i> View Orders</a>-->
                                                <?php }
                                                if (getUserCan('admin_users_module', 'access_delete')) {
                                                ?>
                                                    <a class="dropdown-item delete-user-admin" href="javascript:void(0);" id="<?= $adminList->user_id ?>" data-toggle="modal" data-target="#delete_user_admin"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php $srno++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="add_user_admin" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">Add Admin User</h4>
            </div>
            <div class="modal-body">
                <div class="m-b-30">
                    <?php
                    $form_attribute = array(
                        'name' => 'add-user-admin',
                        'class' => 'form-horizontal',
                        'method' => "post",
                        'id' => 'add-user-admin',
                        'novalidate' => 'novalidate',
                    );
                    $hidden = array('action' => 'addUserAdmin');
                    // Form Open
                    echo form_open_multipart('admin/add_admin_user', $form_attribute, $hidden);
                    ?>
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input class="form-control required" type="text" name="screen_name" id="screen_name">
                    </div>
                    <div class="form-group">
                        <label>Email ID <span class="text-danger">*</span></label>
                        <input class="form-control required email" type="text" name="user_mail" id="user_mail">
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input class="form-control required" type="password" name="user_pass" id="user_pass" minlength="6">
                        <small class="form-text text-muted">Note: Please copy this password for remember when login.</small>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number <span class="text-danger">*</span></label>
                        <div>
                            <input class="form-control required digits" type="text" name="user_phone_no" id="user_phone_no" minlength="10" maxlength="11">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="select required" name="user_active" id="user_active">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive m-t-15">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>Module Permission</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Write</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Delete</th>
                                    <!--<th class="text-center">Import</th>
                                    <th class="text-center">Export</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //pr($arrPermissionsData);
                                $id = 1;
                                foreach ($arrPermissionsData as $cap) {
                                ?>
                                    <tr>
                                        <td><input type="hidden" name="txtcname<?php echo $cap->id; ?>" value="<?php echo $cap->id; ?>" /> <?php echo ucwords($cap->permission_name); ?></td>
                                        <td class="text-center">
                                            <input class="module_access" type="checkbox" name="<?php echo $cap->permission_shortname; ?>_<?= $id++ ?>" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input class="module_access" type="checkbox" name="<?php echo $cap->permission_shortname; ?>_<?= $id++ ?>" value="2">
                                        </td>
                                        <td class="text-center">
                                            <input class="module_access" type="checkbox" name="<?php echo $cap->permission_shortname; ?>_<?= $id++ ?>" value="3">
                                        </td>
                                        <td class="text-center">
                                            <input class="module_access" type="checkbox" name="<?php echo $cap->permission_shortname; ?>_<?= $id++ ?>" value="4">
                                        </td>
                                        <!--<td class="text-center">
                                            <input class="" type="checkbox" name="<?php echo $cap->permission_shortname; ?>_<?= $id++ ?>" value="5">
                                        </td>
                                        <td class="text-center">
                                            <input class="" type="checkbox" name="<?php echo $cap->permission_shortname; ?>_<?= $id++ ?>" value="6">
                                        </td>-->
                                    </tr>
                                <?php $id = 1;
                                } ?>
                            </tbody>
                        </table>
                        <input type="checkbox" class="checkAll" /> <b>Check All</b>
                    </div>
                    <div class="m-t-20 text-center">
                        <button class="btn btn-primary btn-lg" type="submit">Create Admin User</button>
                    </div>
                    <?php
                    // Form Close
                    echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="edit_user_admin" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">Edit Admin User</h4>
            </div>
            <div class="modal-body">
                <div class="m-b-30">
                    <?php
                    $form_attribute = array(
                        'name' => 'edit-user-admin',
                        'class' => 'form-horizontal',
                        'method' => "post",
                        'id' => 'edit-user-admin',
                        'novalidate' => 'novalidate',
                    );
                    $hidden = array('action' => 'editUserAdmin', 'id' => '');
                    // Form Open
                    echo form_open_multipart('admin/add_admin_user', $form_attribute, $hidden);
                    ?>
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input class="form-control required" type="text" name="screen_name" id="screen_name">
                    </div>
                    <div class="form-group">
                        <label>Email ID <span class="text-danger">*</span></label>
                        <input class="form-control required email" type="text" name="user_mail" id="user_mail">
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger"></span></label>
                        <input class="form-control" type="password" name="user_pass" id="user_pass" minlength="6">
                        <small class="form-text text-muted">Note: Please copy this password for remember when login.</small>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number <span class="text-danger">*</span></label>
                        <div>
                            <input class="form-control required digits" type="text" name="user_phone_no" id="user_phone_no" minlength="10" maxlength="11">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="select required" name="user_active" id="user_active">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive m-t-15" id="permission_view">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>Module Permission</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Write</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Delete</th>
                                    <!--<th class="text-center">Import</th>
                                    <th class="text-center">Export</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //pr($arrPermissionsData);
                                $id = 1;
                                foreach ($arrPermissionsData as $cap) {
                                ?>
                                    <tr>
                                        <td><input type="hidden" name="permission_id[]" value="<?php echo $cap->id; ?>" /> <?php echo ucwords($cap->permission_name); ?></td>
                                        <td class="text-center">
                                            <input class="module_access_edit" type="checkbox" name="read_<?= $cap->id ?>" value="Yes">
                                        </td>
                                        <td class="text-center">
                                            <input class="module_access_edit" type="checkbox" name="write_<?= $cap->id ?>" value="Yes">
                                        </td>
                                        <td class="text-center">
                                            <input class="module_access_edit" type="checkbox" name="create_<?= $cap->id ?>" value="Yes">
                                        </td>
                                        <td class="text-center">
                                            <input class="module_access_edit" type="checkbox" name="delete_<?= $cap->id ?>" value="Yes">
                                        </td>
                                        <!--<td class="text-center">
                                            <input class="" type="checkbox" name="import_<?= $cap->id ?>" value="Yes">
                                        </td>
                                        <td class="text-center">
                                            <input class="" type="checkbox" name="export_<?= $cap->id ?>" value="Yes">
                                        </td>-->
                                    </tr>
                                <?php $id = 1;
                                } ?>
                            </tbody>
                        </table>
                        <input type="checkbox" class="checkAllEdit" /> <b>Check All</b>
                    </div>
                    <div class="m-t-20 text-center">
                        <button class="btn btn-primary btn-lg">Save Changes</button>
                    </div>
                    <?php
                    // Form Close
                    echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="delete_user_admin" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h4 class="modal-title">Delete Admin User</h4>
            </div>
            <div class="modal-body card-box">
                <?php
                $form_attribute = array(
                    'name' => 'delete-user-admin',
                    'class' => 'form-horizontal',
                    'method' => "post",
                    'id' => 'delete-user-admin',
                    'novalidate' => 'novalidate',
                );
                $hidden = array('action' => 'deleteUserAdmin', 'user_id' => '');
                //Form Open
                echo form_open_multipart('admin/delete_user_admin', $form_attribute, $hidden);
                ?>
                <p>Do you want to delete the admin user now with his related table data? This cannot be undone.</p>
                <div class="m-t-20"> <a href="javascript:void(0);" class="btn btn-white" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
            <?php
            // Form Close
            echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    $.validator.addMethod("checkEditEmailIdAvailable",
        function(value, element) {
            var result = false;
            user_id = $("form[name=edit-user-admin] input[name='id']").val();
            $.ajax({
                type: "POST",
                async: false,
                dataType: "json",
                url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
                data: "emailId=" + value + "&request=check-admin-email-name&action=edit-user-admin&user_id=" + user_id,
                success: function(data) {
                    console.log(data);
                    //return false;
                    result = (data.dataContent == "0") ? true : false;
                }
            });
            // return true if SHOW NAME is exist in database
            return result;
        },
        "This Email ID is already taken! Try another."
    );

    $.validator.addMethod("checkEmailIdAvailable",
        function(value, element) {
            var result = false;
            $.ajax({
                type: "POST",
                async: false,
                dataType: "json",
                url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
                data: "emailId=" + value + "&request=check-admin-email-name&action=add-user-admin",
                success: function(data) {
                    console.log(data);
                    //return false;
                    result = (data.dataContent == "0") ? true : false;
                }
            });
            // return true if SHOW NAME is exist in database
            return result;
        },
        "This Email ID is already taken! Try another."
    );

    $.validator.addMethod("checkPhoneAvailable",
        function(value, element) {
            var result = false;
            $.ajax({
                type: "POST",
                async: false,
                dataType: "json",
                url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
                data: "phone_no=" + value + "&request=check-admin-phone-no-name&action=add-user-admin",
                success: function(data) {
                    console.log(data);
                    //return false;
                    result = (data.dataContent == "0") ? true : false;
                }
            });
            // return true if SHOW NAME is exist in database
            return result;
        },
        "This Mobile Number is already taken! Try another."
    );

    $.validator.addMethod("checkEditPhoneAvailable",
        function(value, element) {
            var result = false;
            user_id = $("form[name=edit-user-admin] input[name='id']").val();
            $.ajax({
                type: "POST",
                async: false,
                dataType: "json",
                url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
                data: "phone_no=" + value + "&request=check-admin-phone-no-name&action=edit-user-admin&user_id=" + user_id,
                success: function(data) {
                    console.log(data);
                    //return false;
                    result = (data.dataContent == "0") ? true : false;
                }
            });
            // return true if SHOW NAME is exist in database
            return result;
        },
        "This Mobile Number is already taken! Try another."
    );
    /*----------- BEGIN validate CODE -------------------------*/
    $('#add-user-admin').validate({
        ignore: [],
        rules: {
            "user_mail": {
                required: true,
                checkEmailIdAvailable: true,
            },
            "user_phone_no": {
                required: true,
                checkPhoneAvailable: true,
            }
        }
    });
    $('#edit-user-admin').validate({
        ignore: [],
        rules: {
            "user_mail": {
                required: true,
                checkEditEmailIdAvailable: true
            },
            "user_phone_no": {
                required: true,
                checkEditPhoneAvailable: true,
            }
        }
    });

    $("select[name=state_id]").change(function(event, param1, param2) {
        var id = $(this).val();
        if (id > 0) {
            var dataString = 'state_id=' + id + '&request=get_city_category';
            //alert(dataString);
            //return false;
            $.ajax({
                type: "POST",
                url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
                data: dataString,
                cache: false,
                async: false,
                dataType: "json",
                success: function(html) {
                    //alert('sujeet');
                    $("form#edit-user select[name='city_id'],form#add-user select[name='city_id']").html('').html(html.cityList);
                    //$("select[name='category_id']").find("option").eq(0).remove();
                    if (param1 != "") {
                        $("form#edit-user select[name='city_id']").val(param2);
                    }
                }
            });
        }
    });

    function getEditData(user_id) {
        var validator = $("form#edit-user-admin").validate();
        validator.resetForm();
        var dataString = "request=edit_admin_user_data&user_id=" + user_id;
        //alert(dataString);
        //return false;
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "ajax/ajaxProcess",
            dataType: 'json',
            data: dataString,
            success: function(res) {
                //console.log(res.dataContent);
                //return false;
                if (res.dataContent) {
                    if (res.dataContent != '') {
                        $("form[name=edit-user-admin] input[name='id']").val(res.dataContent.user_id);
                        $("form[name=edit-user-admin] #screen_name").val(res.dataContent.screen_name);
                        $("form[name=edit-user-admin] #user_mail").val(res.dataContent.user_mail);
                        $("form[name=edit-user-admin] #user_phone_no").val(res.dataContent.user_phone_no);
                        $("form[name=edit-user-admin] #user_active").val(res.dataContent.user_active).trigger('change');
                        $("form[name=edit-user-admin] #permission_view").html(res.permissionView);
                        //console.log(res.dataContent);
                        $('.module_access_edit').length == $('.module_access_edit:checked').length ? $('.checkAllEdit').prop('checked', true).next().text('Uncheck All') : $('.checkAllEdit').prop('checked', false).next().text('Check All');
                    } else if (res.dataContent == '') {
                        console.log(res);
                    }
                }
            }
        });
    }
    //delete user
    $("body").on('click', '.delete-user-admin', function(event) {
        event.preventDefault();
        var stringArrayId = $(this).prop("id");
        if (stringArrayId > 0) {
            $("form[name=delete-user-admin] input[name='user_id']").val(stringArrayId);
        }
        //alert(stringArrayId);	
    });
    $(document).on('change', '.checkAll', function() {
        $('.module_access').prop('checked', $(this).is(':checked') ? true : false);
        $(this).next().text($(this).is(':checked') ? 'Uncheck All' : 'Check All');
    });
    $(document).on('change', '.module_access', function() {
        $('.module_access').length == $('.module_access:checked').length ? $('.checkAll').prop('checked', true).next().text('Uncheck All') : $('.checkAll').prop('checked', false).next().text('Check All');
    });

    $(document).on('change', '.checkAllEdit', function() {
        $('.module_access_edit').prop('checked', $(this).is(':checked') ? true : false);
        $(this).next().text($(this).is(':checked') ? 'Uncheck All' : 'Check All');
    });
    $(document).on('change', '.module_access_edit', function() {
        $('.module_access_edit').length == $('.module_access_edit:checked').length ? $('.checkAllEdit').prop('checked', true).next().text('Uncheck All') : $('.checkAllEdit').prop('checked', false).next().text('Check All');
    });
</script>