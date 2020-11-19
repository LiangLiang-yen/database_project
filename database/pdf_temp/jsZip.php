<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>測試JSZIP</title>
</head>
<body>
    <input type="file" id="file"/>
    <img src="" id="image">
</body>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jszip.js"></script>
<script type="text/javascript" src="js/cropper.js"></script>
<script type="text/javascript">
    $(function() {
        $("#file").change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            var base64 = "";
            reader.onload = function () {
                var imgData = this.result;
                $("#image").attr("src", imgData);

                var zip = new JSZip();
                // 向zip檔案中新增圖片，可以新增多個檔案或者圖片，此處以圖片為例
                // base64圖片需要去掉base64圖片標識
                zip.file("car.jpg", imgData.substring(imgData.indexOf(",") + 1), {base64: true});
                zip.generateAsync({
                        type: "blob",  // 壓縮型別
                        compression: "DEFLATE",      // STORE：預設不壓縮 DEFLATE：需要壓縮
                        compressionOptions: {
                            level: 9  // 壓縮等級1~9    1壓縮速度最快，9最優壓縮方式
                            // [使用一張圖片測試之後1和9壓縮的力度不大，相差100K左右]
                        }
                    })
                   .then(function (content) {
                    // 壓縮的結果為blob型別
                    console.info(content);
                });
            }
        });
    });
</script>
</html>