function touchImg(bt) {
  bt.style.background = "gray"
}
function outImg(bt) {
  bt.style.background = ""
}
function click(bt) {
  bt.style.background = "white"
}


//確認按鈕按下 將圖片丟給 id=student_photo 的img

function getNaturalWidth() {
  var image = new Image()
  image.src = document.getElementById("blah").src
  fakeWidth = document.getElementById("blah").width
  var naturalWidth = image.width
  return naturalWidth / fakeWidth
}

function clip_img(img_classname) {
  proportion = getNaturalWidth()
  console.log("比例放大:", proportion)
  var canvas = document.getElementById("canvas");
  canvas.width = mask * proportion
  canvas.height = mask * proportion
  var ctx = canvas.getContext("2d");
  var img = document.getElementById("blah");
  img.crossOrigin = "anonymous";
  console.log(img.width)
  console.log(img.height)
  clipImage(img, (cut_left - small_left) * proportion, (cut_top - small_top) * proportion, mask * proportion, mask * proportion, ctx,img_classname);
}


function clipImage(image, clipX, clipY, clipWidth, clipHeight, ctx,img_classname) {
  // draw the image to the canvas
  // clip from the [clipX,clipY] position of the source image
  // the clipped portion will be clipWidth x clipHeight
  console.log("截圖寬度:", clipWidth)
  ctx.drawImage(image, clipX, clipY, clipWidth, clipHeight,
    0, 0, clipWidth, clipHeight);
  var clippedImg = document.getElementsByClassName(img_classname);
  //這裏 canvas.toDataURL() 
    $(clippedImg).attr("src", canvas.toDataURL('image/jpeg',0.6));
  document.getElementById("blah").src = "../images/default.png";
}

function cancel_clip(){
  document.getElementById("blah").src = "../images/default.png";

}

var offsetW = 50;
var img_w, img_h;
var x_temp, y_temp;
var x_R_temp, y_R_temp, Resize_index;
var downflag = false;
var downResizeflag = false;
var cut_top = 0, cut_left = 0;
var max_top, max_left;
var small_top, small_left;
var mask;
function create_Cut_image() {
  //第一層 灰色蓋住沒有被選中的區塊
  //var blah = document.getElementById("")
  console.log("裁減方框遮罩大小: ", mask)
  var cut_layer = document.createElement("div")
  cut_layer.classList.add("cut_layer")
  cut_layer.setAttribute("style", "width:" + mask + "px; height:" + mask + "px;")
  cut_layer.setAttribute("id", "cut_layer")
  cut_layer.setAttribute("onmousedown", "down_cut()")
  cut_layer.setAttribute("onmousemove", "Move_cut()")
  window.addEventListener("mouseup", (arg) => {
    R_before = -1;
    downflag = false;
    downResizeflag = false;
    console.log("release")
  });
  //window.addEventListener("mousemove", Move_cut, false) setting in create_resize_box
  document.getElementById("photo_box").appendChild(cut_layer)
}

function create_resize_box() {
  for (var i = 0; i < 4; i++) {
    var box = document.createElement('div');
    box.classList.add('ve-em');
    switch (i) {
      case 0:
        box.setAttribute("style", "cursor: nw-resize;");
        break;
      case 1:
        box.setAttribute("style", "cursor: ne-resize;");
        break;
      case 2:
        box.setAttribute("style", "cursor: se-resize;");
        break;
      case 3:
        box.setAttribute("style", "cursor: sw-resize;");
        break;
    }
    box.setAttribute("onmousedown", "down_resize("+i+")");
    //box.setAttribute("onmousemove", "Move_cut()")
    window.addEventListener("mousemove", Move_cut, false)
    var rec = document.createElement('div');
    rec.classList.add('ve-fm');
    box.appendChild(rec);
    document.getElementById("photo_box").appendChild(box);
  }
}
function down_resize(i) {
  var e = event || window.event;
  x_R_temp = e.screenX;
  y_R_temp = e.screenY;
  Resize_index = i;
  downResizeflag = true;
  console.log("調整大小起始位置 Index:", Resize_index)
}

//滑鼠拖曳方框
//大頭貼預設進來width是400px 
//剪取方框預設是300px 300px 
//也就是left移動極限為50~150px   top 是 0~100px
function down_cut() {
  var e = event || window.event;
  x_temp = e.screenX;
  y_temp = e.screenY;
  downflag = true;
  console.log("起始位置")
}
function Move_cut() {
  var e = event || window.event;
  if (downflag) {
    var addtop = e.screenY - y_temp
    var addleft = e.screenX - x_temp

    //console.log("方塊 top: ", cut_top, " left: ", cut_left)
    cut_resize(addtop, addleft);
    x_temp = e.screenX;
    y_temp = e.screenY;
  }
  if (downResizeflag) {
    var addtop = e.screenY - y_R_temp;
    var addleft = e.screenX - x_R_temp;
    var dt = (Math.sqrt(Math.pow(addleft, 2) + Math.pow(addtop, 2)))/2;
    switch (Resize_index) {
      case 0:
        dt = addleft < 0 || addtop < 0 ? -dt : dt;
        cut_resize(dt, dt, -dt)
        break;
      case 1:
        dt = addleft < 0 || addtop > 0 ? -dt : dt;
        cut_resize(-dt, 0, dt)
        break;
      case 2:
        dt = addleft < 0 || addtop < 0 ? -dt : dt;
        cut_resize(0, 0, dt)
        break;
      case 3:
        dt = addleft < 0 || addtop > 0 ? -dt : dt;
        cut_resize(0, dt, -dt)
        break;
      default:
        break;
    }
    x_R_temp = e.screenX;
    y_R_temp = e.screenY;
  }
}
function cut_resize(addtop, addleft, addmask = 0) {
  var cut = document.getElementById("cut_layer");
  var big = max_top + mask < max_left + mask ? max_top + mask : max_left -small_left + mask;

  if (mask + addmask <= 50) {
    mask = 50;
    addtop = addleft = 0;
  } else if (mask + addmask > big) {
    mask = big;
    addtop = addleft = 0;
  } else
    mask += addmask;

  if (cut_top + addtop >= max_top)
    cut_top = max_top;
  else if (cut_top + addtop <= small_top)
    cut_top = small_top;
  else
    cut_top = cut_top + addtop;
 
  if (cut_left + addleft >= max_left)
    cut_left = max_left;
  else if (cut_left + addleft <= small_left)
    cut_left = small_left;
  else
    cut_left = cut_left + addleft;

  max_top = img_h - mask;
  max_left = img_w - mask + small_left;
  cut.style.top = cut_top;
  cut.style.left = cut_left;
  cut.style.width = mask;
  cut.style.height = mask;

  $("#photo_box div.vm").each(function (index, element) {
    switch (index) {
      case 0:
        element.style.top = small_top;
        element.style.left = small_left;
        element.style.width = img_w;
        element.style.height = cut_top;
        break;
      case 1:
        element.style.top = cut_top;
        element.style.left = cut_left + mask;
        element.style.width = img_w - (mask + (cut_left - small_left));
        element.style.height = mask;
        break;
      case 2:
        element.style.top = cut_top + mask;
        element.style.left = small_left;
        element.style.width = img_w;
        element.style.height = img_h - cut_top - mask;
        break;
      case 3:
        element.style.top = cut_top;
        element.style.left = small_left;
        element.style.width = cut_left - small_left;
        element.style.height = mask;
        break;
    }
  })
  $("#photo_box div.ve-em").each(function (index, element) {
    switch (index) {
      case 0:
        element.style.top = cut_top - 14;
        element.style.left = cut_left - 14;
        break;
      case 1:
        element.style.top = cut_top - 14;
        element.style.left = cut_left + mask - 14;
        break;
      case 2:
        element.style.top = cut_top + mask - 14;
        element.style.left = cut_left + mask - 14;
        break;
      case 3:
        element.style.top = cut_top + mask - 14;
        element.style.left = cut_left - 14;
        break;
    }
  })
}

//重新設定方框以及 等待圖片載入完成後取得長寬
function get_image_height() {
  $('#blah').on('load', function () {
    downflag = 0
    img_w = $(this).width();
    img_h = $(this).height();
    small = img_w < img_h ? img_w : img_h;
    mask = small <= 200 ? small : 200;
    max_top = img_h - mask;
    max_left = img_w - mask + small_left;
    cut_top = this.offsetTop;
    cut_left = this.offsetLeft;
    small_top = this.offsetTop;
    small_left = this.offsetLeft;
    console.log("遮罩可移動範圍 x: 50 ~ ", max_left, " y: 0 ~ ", max_top)
    console.log("遮罩大小:", mask)
    console.log("top:", this.offsetTop, "left:", this.offsetLeft)
    var cut_layer = document.getElementById("cut_layer");
    cut_layer.style.top = cut_top;
    cut_layer.style.left = cut_left;
    cut_layer.style.width = mask;
    cut_layer.style.height = mask;

    if (small != 0) {
      cut_resize(0, 0);
      $(".cut_layer").show();
      $(".vm").show();
      $(".ve-em").show();
    } else {
      $(".cut_layer").hide();
      $(".vm").hide();
      $(".ve-em").hide();
    }
  });
}

function load_image_table(image_table_title, img_classname = "head-shot") {
  var div = document.createElement('div')
  div.setAttribute("style", "z-index:2;")
  div.innerHTML = '<div class="modal fade" data-backdrop="static" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
                <div class="modal-dialog">\
                <div class="modal-content" >\
                  <div class="modal-header">\
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\
                    <h3 id="myModalLabel">' + image_table_title +'</h3>\
                  </div>\
                  <div class="modal-body row">\
                    <div  class="col-sm-12" id="move_plot" style="text-align:center;">\
                      <div id="photo_box" >\
                        <img id="blah" src="../images/default.png" style="width:100%;height:auto;">\
                        <canvas id="canvas" style="display:none;" ></canvas>\
                        <div>\
                          <div class="vm"></div>\
                          <div class="vm"></div>\
                          <div class="vm"></div>\
                          <div class="vm"></div>\
                        </div>\
                      </div>\
                    </div>\
                    <div class="col-sm-12" style="margin-top: 10px;">\
                      <div class="col-sm-8">\
                        <form runat="server" style="text-align:center;">\
                            <input type="file" onchange="get_image_height()" accept="image/gif, image/jpeg, image/png" id="imgInp" />\
                        </form>\
                      </div>\
                    </div>\
                  </div>\
                  <div class="modal-footer">\
                    <button type="button" data-dismiss="modal" onclick="clip_img(\''+img_classname+'\')" class="btn btn-info">確認</button>\
                    <button type="button" data-dismiss="modal" onclick="cancel_clip()" class="btn btn-light">取消</button>\
                </div>\
                </div>\
              </div>'
  document.getElementsByTagName('body')[0].appendChild(div);
  create_Cut_image()
  create_resize_box()
  get_image_height()
}


var temp_content = "";
var temp = document.createElement("div");
if (typeof student_name_ == 'undefined') {
  student_name_ = "無";
}
if (typeof student_email == 'undefined') {
  student_email = "無";
}
if (typeof student_phoneNum == 'undefined') {
  student_phoneNum = "無";
}
if (typeof student_imagePATH == 'undefined') {
  student_imagePATH = "https://www.w3schools.com/bootstrap4/img_avatar1.png";
}
if (typeof student_studentId == 'undefined') {
  student_studentId = "無";
}
function load_temp() {
  temp_content += '<nav class="navbar navbar-default navbar-fixed-top" style="background:white;border:0;">\
    <div class="container-fluid">\
      <div class="navbar-header">\
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">\
        <span class="icon-bar"></span>\
        <span class="icon-bar"></span>\
        <span class="icon-bar"></span>\
        </button>\
        <a class="navbar-brand" href="#" style="font-size:36px;margin-left:15px;" >\
          <img src="https://www.nfu.edu.tw/images/logo.png" style="height:30px;">\
        </a>\
      </div>\
      <div class="collapse navbar-collapse" id="myNavbar">\
        <ul class="nav navbar-nav">\
          <li><a href="form.php" style="font-family:serif;font-size:16px;">表單填寫</a></li>\
          <li ><a href="changeinfo.php" style="font-family:serif;font-size:16px;">個人資料修改</a></li>\
        </ul>\
      <div>\
      <ul class="nav navbar-nav navbar-right" style="margin-right:10px;">\
        <li class="dropdown "></li>\
          <li class="dropdown "><a  href="#"  class="dropdown-toggle" data-toggle="dropdown"><img id="student_photo" class="head-shot" onmouseover="touchImg(this)" onmouseout="outImg(this)" style="border:0.2px solid white; border-radius:50%;width:50px;height:50px;" src="' + student_imagePATH + '"></a>\
                <div class="card dropdown-menu dropdown-menu-right" style="padding: 2px;background:white;color:black;width:350px;height:450px;text-align:center">\
                  <img class="card-img-top head-shot" src="'+ student_imagePATH + '" alt="Card image cap" style="margin:10px 0 10px 0;border-radius:50%; width:200px; height: 200px;">\
                  <a id="import_image" href="changeinfo.php" class="btn btn-default glyphicon glyphicon-camera" style="color:black;border-radius:50%;background:white;position:absolute;left: 220px;top: 150px;z-index:1;font-size:30px;"></a>\
                  <div class="card-body" style="margin:10px;font-size:16px;">\
                    <P class="card-title"><span class="glyphicon glyphicon-user"></span>&nbsp;'+ student_name_ + '</p>\
                    <p class="card-text">學號&nbsp;'+ student_studentId + '</p>\
                    <p class="card-text"><span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;'+ student_email + '</p>\
                    <p class="card-text"><span class="glyphicon glyphicon-earphone"></span>&nbsp;&nbsp;'+ student_phoneNum + '</p>\
                    <hr style="hright:5px;">\
                    <div class="col-sm-6"><a class="btn btn-default" style="color:black;" href="changeinfo.php" ><span class="glyphicon glyphicon-pencil"></span> 更改資料</a></div>\
                    <div class="col-sm-6"><a class="btn btn-default" style="color:black;" href="../logout.php" ><span class="glyphicon glyphicon-log-out"></span> 登出</a></div>\
                  </div>\
                </div>\
          </li>\
      </ul>\
    </div>\
  </nav>'
  temp.innerHTML = temp_content;
  document.getElementsByTagName('body')[0].insertBefore(temp, document.body.childNodes[0])
}
