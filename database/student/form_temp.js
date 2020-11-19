var CformType
var CstudentId

function load_table_list() {
  $.ajax({
    type: 'post',
    url: '../class/Class_Student_form.php',
    data: {
      actionForm: 'loadList'
    },
    dataType: 'json',
    success: function (jsonData) {
      var content = "";
      content += '<div class="col-sm-12"><h3>表單列表</h3></div>';
      if (parseInt(jsonData[0].haveData) == 1) {
        for (var i = 1; i < jsonData.length; i++) {
          var canWriteStatus = parseInt(jsonData[i].canWrite)
          var showStatus = "hid";
          var writeClass = ""
          var disabled="";
          var labelType="label-default"

          console.log(canWriteStatus);
          if (parseInt(jsonData[i].endStatus) == 1) {
            canWriteStatus = 0;
            showStatus = "";
            labelType="label-success"
          }
          if (canWriteStatus == 0) {
            writeClass = "btn-danger"
            disabled='disabled="disabled" '
          }
          else
            {
              writeClass = "btn-success"
            }
          console.log(jsonData[i].endStatus);
          console.log(canWriteStatus);
          console.log(jsonData[i].sendStatus);
          content += '\
                  <div class="list-group">\
                    <div href="form.html" class="list-group-item col-sm-12">\
                      <div class="col-sm-2" style="margin:5px 0 0 0;">' + jsonData[i].formType + '</div>\
                      <div class="col-sm-2" style="margin:10px 0 0 0; ><span class="label '+labelType+'">' + (parseInt(jsonData[i].endStatus) ? '已銷案' : '未銷案') + '</span></div>\
                      <div class="col-sm-2">\
                        <button class="btn '+ writeClass + '" '+disabled+'" onclick=' + (canWriteStatus ? '"head_content(\'' + jsonData[i].formType + '\',' + 0 + ')" href="#" ' : "") + '>' + (canWriteStatus ? '填寫/修改表單' : '不可填寫/修改表單') + '</button>\
                      </div>\
                      <div class="col-sm-2 '+ showStatus + '">\
                      <button class="btn btn-info " onclick=head_content(\'' + jsonData[i].formType + '\'' + ',' + 1 + ') href="#">查看表單內容</button>\
                      </div>\
                    </div>\
                  </div>';

        }


        document.getElementById("student_form").innerHTML += content;
      }
    }
  })
}


function head_content(formType, showStatus) {
  $("#title").html("表單填寫");
  var content = "";
  $.ajax({
    type: 'post',
    url: '../class/Class_Student_form.php',
    data: {
      actionForm: 'getProfile',
      formType: formType
    },
    dataType: 'json',
    success: function (jsonData) {
      console.log(jsonData)
      if (jsonData[0].findProfile == 1) {

        content += ' <span class="hid" id="formId">' + jsonData[1].formId + '</span><div  class="col-sm-12" ><div  class="col-sm-1"></div><div  class="col-sm-10" >\
        <div class="col-sm-12" style="text-align:center">\
          <label><h2>國立虎尾科技大學 '+ jsonData[1].year + ' 學年度第 ' + jsonData[1].term + ' 學期<br>賃居校外學生輔導訪問記錄表</h2></label>\
        </div>\
        <div class="col-sm-12 mtop">\
          <div class="col-sm-6 mtop">\
            <div class="input-group mtop">\
              <span class="input-group-addon">房東姓名</span>\
              <input id="landlordName" type="text" class="form-control" value="'+ jsonData[1].landlordName + '" name="landlordName" placeholder="e.g.王小明 " >\
            </div>\
          </div>\
          <div class="col-sm-6 mtop mtop">\
            <div class="input-group mtop">\
              <span class="input-group-addon">房東電話</span>\
              <input id="landlordPhone" type="text" class="form-control" value="'+ jsonData[1].landlordPhone + '" name="landlordPhone" placeholder="e.g. 0912345678、051234567">\
            </div>\
          </div>\
        </div>\
        <div class="col-sm-12 mtop">\
          <div class="col-sm-12 mtop">\
            <div class="input-group mtop">\
              <span class="input-group-addon">賃居地址</span>\
              <input id="address" type="text" class="form-control" value="'+ jsonData[1].address + '" name="address" placeholder="e.g. 雲林縣虎尾鎮文化路64號">\
            </div>\
          </div>\
        </div>\
        <!-- 不給更改個人基本資料 -->\
        <div class="col-sm-12 mtop">\
          <div class="col-sm-4 mtop">\
            <div class="input-group col-sm-12 mtop">\
              <span class="input-group-addon " style="width:30%">班級</span>\
              <span class="input-group-addon" style="padding:0;width:70%;"><select id="class_" style="height:32px;width:100%;" class="form-control">\
              <option >四資工一甲</option>\
              <option >四資工一乙</option>\
              <option >四資工二甲</option>\
              <option >四資工二乙</option>\
              <option >四資工三甲</option>\
              <option >四資工三乙</option>\
              <option >四資工四甲</option>\
              <option >四資工四乙</option>\
              </select></span>\
            </div>\
          </div>\
          <div class="col-sm-4 mtop">\
            <div class="input-group col-sm-12 mtop">\
              <span class="input-group-addon ">學號</span>\
              <input id="studentId" type="text" class="form-control" disabled="disabled" value="' + jsonData[1].userId + '"name="studentId" placeholder="">\
            </div>\
          </div>\
          <div class="col-sm-4 mtop">\
            <div class="input-group col-sm-12 mtop">\
              <span class="input-group-addon ">姓名</span>\
              <input id="studentName" type="text" class="form-control" disabled="disabled" value="' + jsonData[1].name + '"name="studentName" placeholder="">\
            </div>\
          </div>\
        </div></div>';

        CstudentId = jsonData[1].userId;
        document.getElementById("student_form").innerHTML = content;
        body_content(formType, showStatus, jsonData[1].studentclass);
      }
      else {
        alert("找不到資料，請洽管理員");
        window.location.reload();
      }
    }
  })
}

function body_content(formType, showStatus, studentclass) {
  var imagePATH = ""
  var content = document.getElementById("student_form").innerHTML;
  content += '<div  class="col-sm-12" ><div  class="col-sm-1"></div>\
    <div class="col-sm-10 mtop" id="left-content">\
    <div class="col-sm-12 mtop"><label>訪問項目與情形</label></div>';
  // console.log(formType);
  $.ajax({
    type: 'post',
    url: '../class/Class_Student_form.php',
    data: {
      actionForm: 'loadQuestion',
      formType: formType
    },
    dataType: 'json',
    success: function (jsonData) {
      console.log(jsonData);
      // console.log(JSON.stringify(jsonData))
      if (jsonData.formIsset == 1) {
        var radioCount = jsonData.questionForm.length;
        var goodcheckStatus = "checked"
        var badcheckStatus = ""
        var description = ""
        var imgPATH = ""

        for (var i = 0; i < jsonData.questionForm.length; i++) {
          imgPATH = ""
          for (var k = 0; k < jsonData.exceptQuestion.length; k++) {
            goodcheckStatus = "checked"
            badcheckStatus = ""
            description = ""

            num = jsonData.exceptQuestion[k].num
            console.log(num)
            if (i == parseInt(num)) {
              goodcheckStatus = ""
              badcheckStatus = "checked"
              description = jsonData.exceptQuestion[k].description
              if (jsonData.exceptQuestion[k].imgPATH != "")
                imgPATH = jsonData.exceptQuestion[k].imgPATH
              else
                imgPATH = ""
              console.log(description)
              break;
            }
          }
          //預設良好為Checked
          content += '\
            <div class="col-sm-6 mtop radiotable" id="radioTable'+ i + '"><label>&nbsp;&nbsp;' + (i + 1) + '.' + jsonData.questionForm[i].questionName + '</label><br>\
                <label class="radio-inline"  style="margin-left:8px;"><input  type="radio" value="good" id="optradio' + i + '" ' + goodcheckStatus + ' >良好</label>\
                <label class="radio-inline"><input  type="radio" value="bad" id="optradio'+ i + '" ' + badcheckStatus + '>異常</label>&nbsp\
                <button type="button" id="showimg'+ i + '" onclick="load_image(\'' + imgPATH + '\')" class="btn btn-success btn-sm hid" data-toggle="modal" data-target="#myModal2">查看圖片</button>'

          content += '\
                <div class="form-group hid" name="textarea" id="dsp'+ i + '" style="margin-left:8px;">\
                    <br><label for="comment">描述異常狀況:</label>\
                    <textarea  class="form-control" rows="2" id="comment'+ i + '" style="resize:none;"   >' + description + '</textarea>\
                    <img id="img_'+ (i) + '" class="hid" src="' + imgPATH + '" width="40%"><br>\
                    <button type="button" id="exbtn'+ i + '" onclick="load_image(\'' + imgPATH + '\',' + i + ')" class="btn btn-primary" data-toggle="modal" data-target="#myModal">上傳圖片</button>\
                </div>\
            </div>'

        }
        content += '<div class="col-sm-12 mtop">\
        <div class="col-sm-12 mtop">\
            <div class="form-group hid" id="teacher_comment_area">\
            <label>訪問要點及學生意見</label>\
            <textarea class="form-control" style="height:100px;resize:none;" id="teacher_comment" disabled>'+ jsonData.teacherDescription + '</textarea>\
            </div>\
        </div>\
    </div>'

        content += '<div class="col-sm-12" style="margin-top:20px;">\
                      <div class="col-sm-1" ></div>\
                      <div class="col-sm-10" >\
                        <div  class="col-sm-4"></div>\
                        <div  class="col-sm-4"></div>\
                        <div class="col-sm-4 bt-group" style="text-align:right">\
                          <button class="btn btn-info hid" onclick="btnDownloadPageBypfd2(' + "'#pdf_container'" + ')">pdf列印</button>\
                          <button onclick="student_form_submit('+ radioCount + ')" class="btn btn-info" id="submitForm">儲存表單</button>\
                        </div>\
                      </div>\
                    </div>'
        //console.log(content)
        document.getElementById("student_form").innerHTML = content;

        for (var k = 0; k < radioCount; k++) {
          if ($('#img_' + k).attr('src') != "")
            $('#showimg' + k).removeClass('hid')
          $('#img_' + k).on('load', function () {
              k=$(this)[0].id.replace('img_',"")
              var src_ = $('#img_' + k).attr('src')
              $('#showimg' + k).removeClass('hid')
              $('#showimg' + k).attr('onclick', 'load_image(\''+src_+'\')')
          })
        }

        $(document).attr("title","填寫/修改表單");

        $('.select,#class_').val(studentclass)

        if (showStatus == 1)
          showTable(radioCount);

        load_descript();
        CformType = formType

      } else {
        alert("找不到表單，請洽管理員");
        window.location.reload();
      }

    }
  })
}

function showTable(radioCount) {
  $('#landlordName').attr('disabled', 'disabled');
  $('#landlordPhone').attr('disabled', 'disabled');
  $('#address').attr('disabled', 'disabled');
  $('.input-group-addon,#class_').attr('disabled', 'disabled');

  $('.form-group,#teacher_comment_area').removeClass('hid');
  $('.button,#submitform').addClass('hid')
  $(document).attr("title","查看表單內容");

  for (var i = 0; i < radioCount; i++) {
    $('#exbtn' + i).addClass('hid')
    $('.input,#optradio' + i).attr('disabled', 'disabled')
    if ($('input[id=optradio' + i + ']:checked').val() == 'bad') {
      $('textarea[id=comment' + i + ']').attr('disabled', 'desabled')
      $('#showimg' + i).removeClass('hid')
    }
  }
}

function load_descript() {
  var radios = document.getElementsByClassName("radiotable")

  for (var i = 0; i < radios.length; i++) {
    var g = radios[i].getElementsByTagName("input")[0]; //良好
    var b = radios[i].getElementsByTagName("input")[1]; //異常
    g.name = i;
    b.name = i;
    console.log(g.name)
    if (g.checked) {
      g.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group hid"
      console.log(g)
    }
    if (b.checked) {
      b.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group"
      console.log(b)
    }
    g.onclick = function () {
      this.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group hid"
      //answer[this.name] = 1;
      // set_comment_height()
    }
    b.onclick = function () {
      this.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group"
      // answer[this.name] = 0;
      //set_comment_height()
    }
  }
}

//---------開啟圖片視窗---------//

var load_image_flag = 0;
var CquestionNum;
function load_image(img_path, questionNum) {
  CquestionNum = questionNum
  if (load_image_flag == 0) {
    var table = document.createElement("div")
    table.id = "show_student_image"
    document.getElementsByTagName("body")[0].append(table)
    load_image_flag = 1
  }
  document.getElementById("show_student_image").innerHTML = '\
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
      <div class="modal-dialog" role="document">\
          <div class="modal-content" style="width: 650px;">\
              <div class="modal-header">\
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span\
                          aria-hidden="true">×</span></button>\
                  <h3 class="modal-title" id="myModalLabel" style="text-align: center;">異常圖片</h3>\
              </div>\
              <div class="modal-body" style="text-align: center;">\
                  <img src="' + img_path + '"\
                      style="width: 600px;height:400px;">\
              </div>\
              <div class="modal-footer">\
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
              </div>\
          </div>\
      </div>\
  </div>'
}


function student_form_submit(radioCount) {
  var landlordName = $('input[id="landlordName"]').val();
  var landlordPhone = $('input[id="landlordPhone"]').val();
  var address = $('input[id="address"]').val();
  var studentclass = $('#class_ option:selected').text();
  if (landlordName == "" || landlordPhone == "" || address == "" || studentclass == "") {
    alert("資料不可為空喔");
  }
  else {
    landlordPhone = landlordPhone.match(/[0]{1}[0-9]{8,9}/gi);
    landlordName = landlordName.match(/[\u4e00-\u9fffa-zA-z]{1,}/gi);
    // studentclass = studentclass.match(/[\u4e00-\u9fff]{4}/gi);
    // address = address.match(/[\u4e00-\u9fffa-zA-z0-9\-]{1,}/);
    if (landlordPhone == null) {
      alert("房東電話輸入錯誤喔");
      $('input[id="landlordPhone"]').val("");
    }
    if (landlordName == null) {
      alert("房東姓名輸入錯誤喔");
      $('input[id="landlordName"]').val("");
    }
    // if (studentclass == null) {
    //   alert("班級輸入錯誤喔");
    //   $('input[id="studentclass"]').val("");
    // }

    var questionArr = [];
    var nullFlag = 0
    for (var i = 0; i < radioCount - 1; i++) {
      console.log(i + " " + $('input[id=optradio' + i + ']:checked').val());
      if ($('input[id=optradio' + i + ']:checked').val() == "bad") {
        console.log($('textarea[id=comment' + i + ']').val());
        if ($('#comment' + i).val() == "") {
          alert('異常狀況描述不可為空:第' + (i + 1) + "題")
          if ($('#img_' + i).attr('src') == "")
            alert('異常狀況照片未上傳:第' + (i + 1) + "題")
          nullFlag = 1
        }
        else if ($('#img_' + i).attr('src') == "") {
          alert('異常狀況照片未上傳:第' + (i + 1) + "題")
          if ($('#comment' + i).val() == "")
            alert('異常狀況描述不可為空:第' + (i + 1) + "題")
          nullFlag = 1
        }
        else {
          questionArr.push({
            num: i,
            description: $('textarea[id=comment' + i + ']').val(),
            image: $('#img_' + i)[0].src
            // image: ""
          });
        }
      }
    }
    if (landlordName != null && studentclass != null && landlordPhone != null && nullFlag == 0) {
      var formId = CformType.replace("-", '0') + CstudentId;

      var jsonInputData = {
        landlordName: landlordName,
        landlordPhone: landlordPhone,
        address: address,
        studentclass: studentclass,
        question: questionArr,
        formId: formId
      }
      console.log(jsonInputData);
      var submitData = JSON.stringify(jsonInputData);
      console.log(submitData);

      $.ajax({
        type: 'post',
        url: '../class/Class_Student_form.php',
        data: {
          actionForm: 'insertData',
          jsonStr: submitData
        },
        dataType: 'json',
        timeout: 30000,
        success: function (jsonData) {
          console.log(jsonData);
          if (jsonData.status == "success") {
            alert("填寫成功!");
          }
          else {
            alert("填寫失敗，請洽管理員！")
          }
          window.location.reload();
        }
      });
    }
  }
}