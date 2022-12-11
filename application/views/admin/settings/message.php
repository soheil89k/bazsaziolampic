<?php
defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>


<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">

                <ul class="this-page-nav nav nav-tabs">
                    <?php
                    foreach ($groups as $group) {
                        $class = $group['id'] == $tabId ? 'active' : '';
                        echo '<li role="presentation" class="' . $class . '" onClick="changeTab(' . $group['id'] . ')" id="tab_btn_' . $group['id'] . '"><a href="#">' . $group['groupName'] . '</a></li>';
                    }
                    ?>
                </ul>


                <!-- Modal -->

                <div class="table-responsive">
                    <button type="button" class="btn btn-success" onclick="openAddNewModal()">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        اضافه
                    </button>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>
                                ردیف
                            </th>
                            <th>
                                گروه
                            </th>
                            <th>
                                متن پیام
                            </th>
                            <th>
                                تنظیمات
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach ($messages as $i => $m) {

                            $index = $i + 1;
                            $groupId = $m['groupId'];
                            $groupName = $m['groupName'];
                            $text = $m['text'];
                            echo '<tr data-group-id="' . $groupId . '">';
                            echo "<td>$index</td>";
                            echo "<td>$groupName</td>";
                            echo "<td><div style='white-space: pre-line'>$text</div></td>";
                            echo '<td><div class="action-cell"><span class="glyphicon glyphicon-pencil" onclick="edit(' . $i . ')" aria-hidden="true"></span><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></div></td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>


    </div>

</div>


<div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    ثبت قالب جدید برای پیام
                </h5>
            </div>
            <div class="modal-body">


                <div class="form-group">
                    <label for="exampleInputEmail1">
                        گروه پیام
                    </label>
                    <select class="form-control" id="groupSelector">
                        <option value="0">
                            انتخاب
                        </option>
                        <?php
                        foreach ($groups as $group) {
                            echo "<option value='{$group['id']}'>{$group['groupName']}</option>";
                        }
                        ?>

                    </select>
                    <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">
                        متن پیام
                    </label>
                    <textarea class="form-control" placeholder="متن پیام" rows="10" id="messageText"
                              aria-label="" aria-describedby="basic-addon2"></textarea>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    انصراف
                </button>
                <button id="submitBtn" class="btn btn-primary">
                    ثبت
                </button>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>

<style>
    .action-cell {
        display: flex;
    }

    .action-cell span {
        margin: 3px;
        padding: 5px;
        cursor: pointer;
    }

    .action-cell span:hover {
        color: #888888;
    }
</style>
<script>
    var url = '<?php echo APP_BASE_URL?>/admin/setting/message';
    var messagesList = <?php echo json_encode($messages)?>;
    var mode = 'insert';
    var selectedMessage = {};
    document.addEventListener("DOMContentLoaded", () => {


        $("#submitBtn").click(function (e) {
            e.preventDefault();

            switch (mode) {
                case 'insert': {
                    var data = {
                        group: $('#groupSelector').val(),
                        text: $('#messageText').val(),
                    }
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data, status) {
                            alert("اطلاعات با موفقیت ثبت شد");
                            $('#groupSelector').val(0)
                            $('#messageText').val('')
                            $('#addNewModal').modal('toggle');
                            window.location.reload();
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert("خطا در ثبت اطلاعات");
                        }
                    });
                    break;
                }
                case 'remove': {

                    break;
                }
                case 'edit': {
                    var data = {
                        id: selectedMessage.messageId,
                        group: $('#groupSelector').val(),
                        text: $('#messageText').val(),
                    }
                    $.ajax({
                        type: "PUT",
                        url: url,
                        data: data,
                        success: function (data, status) {
                            alert("اطلاعات با موفقیت بروزرسانی شد");
                            $('#groupSelector').val(0)
                            $('#messageText').val('')
                            $('#addNewModal').modal('toggle');
                            window.location.reload();
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert("خطا در بروزرسانی اطلاعات");
                        }
                    });
                    break;
                }
            }

        });


    });

    function changeTab(groupId) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tg=' + groupId;
        window.location.replace(newurl);

    }

    function openAddNewModal() {
        mode = 'insert';
        $('#addNewModal').modal();
    }

    function edit(index) {
        selectedMessage = messagesList[index];
        mode = 'edit';
        $('#groupSelector').val(selectedMessage.groupId)
        $('#messageText').val(selectedMessage.text)
        $('#addNewModal').modal();
    }

    function remove(index) {
        selectedMessage = messagesList[index];
        mode = 'delete';
    }

</script>