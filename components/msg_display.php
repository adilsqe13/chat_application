<!-- Message display -->
<div style="height: 20px">
                <h5 style="color: #03fc66 ;" class="msg-display" align='center'><?php if (isset($_GET['msg'])) {
                                                                                    $msg = $_GET['msg'];
                                                                                    if (!$_GET['status']) {
                                                                                        echo "<span style='color:red'>$msg</span>";
                                                                                    } else {
                                                                                        echo $msg;
                                                                                    }
                                                                                } ?></h5>
            </div>