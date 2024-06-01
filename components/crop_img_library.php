<script>
    $(document).ready(function() {
        var $modal = $('#modal');
        var image = document.getElementById('sample_image');
        var cropper;

        $('#upload_image').change(function(event) {
            var files = event.target.files;
            var done = function(url) {
                image.src = url;
                $modal.modal('show');
            };

            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function(event) {
                    done(reader.result);
                };
                reader.readAsDataURL(files[0]);
            }
        });

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $('#crop').click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                const userId = document.getElementById('user_profile_input').value;
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        url: '../handle_operation/handle_update_img.php',
                        method: 'POST',
                        data: {
                            image: base64data,
                            userId: userId
                        },
                        success: function(data) {
                            window.location.reload();
                        }
                    });
                };
            });
        });
    });
</script>