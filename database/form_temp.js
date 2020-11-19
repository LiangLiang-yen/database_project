/*
  var landlord_name 房東姓名
  var landlord_pnumber  房東電話號碼
  var student_addr 學生租屋地址
  var student_class 學生班級
  var student_ID  學號
  var student_name  學生姓名

  表單問題陣列
  var question = ["李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?"]

  學生回答正常或異常陣列
  var answer = [1,0,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]

  判斷學生是否勾選異常 若有異常則顯示問題描述框
  function load_descript() 

  載入學生及房東基本資料填寫欄位
  function load_form()
*/


//房東姓名 房東電話號碼 學生租屋地址 學生班級 學號 學生姓名
var student_data = ["怪人王","87878787","A市地底","資工三甲","2312312313","葉彥良"]
var questiondesign = ["設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常","設備是否正常"]
//0是良好 1是異常
var answer = [1,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
var student_description = ["很爛","","","","","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛","很爛"]
var imagePATH = ["1.JPG","","","","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG","1.JPG"]
var teacher_description = "DSJAIOPDJASOIDJAOISDJOAJDOI"

//判斷學生是否勾選異常 若有異常則顯示問題描述框
function load_descript()
{
    var radios = document.getElementsByClassName("radiotable")
    
    for(var i =0 ;i < radios.length;i++){
        var g = radios[i].getElementsByTagName("input")[0]; //良好
        var b = radios[i].getElementsByTagName("input")[1]; //異常
        g.name = i;
        b.name = i;
        console.log(g.name)
        if(g.checked)
        {
            g.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group hid"
            console.log(g)
        }
        if(b.checked)
        {
            b.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group"
            console.log(b)
        }
        g.onclick = function(){
          this.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group hid"
          answer[this.name] = 1;
          set_comment_height()
        }
        b.onclick = function(){
          this.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group"
          answer[this.name] = 0;
          set_comment_height()
        }
  }
}

function head_content(){
  var content ='<div class="col-sm-1"> </div>';
  content += '<div  class="col-sm-10" >\
      <div class="col-sm-12" style="text-align:center">\
        <label><h2>國立虎尾科技大學 108 學年度第 一 學期<br>賃居校外學生輔導訪問記錄表</h2></label>\
      </div>\
      <div class="col-sm-12 mtop">\
        <div class="col-sm-6 mtop">\
          <div class="input-group mtop">\
            <span class="input-group-addon">房東姓名</span>\
            <input id="msg" type="text" class="form-control" value="'+ student_data[0] +'" name="msg" placeholder="Additional Info">\
          </div>\
        </div>\
        <div class="col-sm-6 mtop mtop">\
          <div class="input-group mtop">\
            <span class="input-group-addon">房東電話</span>\
            <input id="msg" type="text" class="form-control" value="' + student_data[1] + '" name="msg" placeholder="Additional Info">\
          </div>\
        </div>\
      </div>\
      <div class="col-sm-12 mtop">\
        <div class="col-sm-12 mtop">\
          <div class="input-group mtop">\
            <span class="input-group-addon">賃居地址</span>\
            <input id="msg" type="text" class="form-control" value="' + student_data[2] + '"name="msg" placeholder="Additional Info">\
          </div>\
        </div>\
      </div>\
      <!-- 不給更改個人基本資料 -->\
      <div class="col-sm-12 mtop">\
        <div class="col-sm-2 mtop"><label>賃居學生</label>        </div>\
        <div class="col-sm-3 mtop">\
          <div class="input-group col-sm-12 mtop">\
            <span class="input-group-addon ">班級</span>\
            <input id="msg" type="text" class="form-control" value="' + student_data[3] + '"name="msg" placeholder="">\
          </div>\
        </div>\
        <div class="col-sm-4 mtop">\
          <div class="input-group col-sm-12 mtop">\
            <span class="input-group-addon">學號</span>\
            <input id="msg" type="text" class="form-control" value="' + student_data[4] + '"name="msg" placeholder="">\
          </div>\
        </div>\
        <div class="col-sm-3 mtop">\
          <div class="input-group col-sm-12 mtop">\
            <span class="input-group-addon">姓名</span>\
            <input id="msg" type="text" class="form-control" value="' + student_data[5] + '"name="msg" placeholder="">\
          </div>\
        </div>\
      </div>'
  return content;
}



function body_cntent(){
    var content = "";
    content += '\
      <div class="col-sm-12 mtop" id="left-content">\
      <div class="col-sm-12 mtop"><label>訪問項目與情形</label></div>'
    for(var i =0;i <  questiondesign.length;i++)
    {
    var normal = ""
    var abnormal = ""
    if(answer[i] == 0){
        normal = "checked"
    }else {
        abnormal = "checked"
    }
    content += '\
          <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;' + (i+1) + '.' +  questiondesign[i] +'</label><br>\
              <label class="radio-inline"><input  disabled="disabled"  type="radio" name="optradio' + i +'"' + normal + '>良好</label>\
              <label class="radio-inline"><input  disabled="disabled"  type="radio" name="optradio' + i + '"'+ abnormal + '>異常</label>&nbsp;\
              <button type="button" onclick="load_image(\'' + imagePATH[i] + '\')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal' + i +'">查看圖片</button>'
    
    content +='\
              <div class="form-group hid" name="textarea" id="dsp">\
                  <label for="comment">描述異常狀況:</label>\
                  <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea>\
              </div>\
          </div>'
    }
    content += "</div>"

    //訪問要點及學生意見 跟 儲存送出鈕
    content += '<div class="col-sm-12 mtop">\
                    <div class="col-sm-12 mtop">\
                        <div class="form-group">\
                        <label>訪問要點及學生意見</label>\
                        <textarea class="form-control" style="height:100px;resize:none;" id="teacher_comment"></textarea>\
                        </div>\
                    </div>\
                </div>'

    content += '<div class="col-sm-12" >\
                  <div class="col-sm-12 bt-group" style="text-align:right">\
                    <button class="btn btn-info" onclick="btnDownloadPageBypfd2(' +"'#pdf_container'" + ')">pdf列印</button>\
                    <button onclick="load_student_form()" class="btn btn-info">儲存表單</button>\
                  </div>\
                </div>'
    return content;
}


//載入學生及房東基本資料填寫欄位
function load_student_form(){ 
      var form_content = ""
      form_content += head_content();
      form_content += body_cntent();                    
      document.getElementById("student_form").innerHTML = form_content;
      load_descript()
      set_comment_height()
} 

//按下button可開啟查看圖片視窗
//img_path 圖片路徑 number 問題編號
var load_image_flag = 0;
function load_image(img_path){
  if(load_image_flag == 0)
  {
    var table = document.createElement("div")
    table.id = "show_student_image"
    document.getElementsByTagName("body")[0].append(table)
    load_image_flag = 1
  }
  document.getElementById("show_student_image").innerHTML = '\
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
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
  $("#myModal").modal()
}