<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="index.css">
  <title>视屏播放</title>
  <style>
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed, 
    figure, figcaption, footer, header, hgroup, 
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
      margin: 0;
      padding: 0;
      border: 0;
      font-size: 100%;
      font: inherit;
      vertical-align: baseline;
    }
    /* HTML5 display-role reset for older browsers */
    article, aside, details, figcaption, figure, 
    footer, header, hgroup, menu, nav, section {
      display: block;
    }
    body {
      line-height: 1;
    }
    ol, ul {
      list-style: none;
    }
    blockquote, q {
      quotes: none;
    }
    blockquote:before, blockquote:after,
    q:before, q:after {
      content: '';
      content: none;
    }
    table {
      border-collapse: collapse;
      border-spacing: 0;
    }

    /* 样式 */

    .video_wrap {
      position: relative;
      width: 100%;
      overflow: hidden;
    }
    #videoInfoId {
    }
    .video_wrap .poster {
      position: absolute;
      width: 100%;
      left: 0px;
      top: 0px;
    }
    .video-btn-wrap {
      position: absolute;
      right: 20px;
      bottom: 20px;
      z-index: 99;
    }
    .video-percentage-bar {
      position: absolute;
      width: 100%;
      height: 2px;
      background-color: #cfa948;
      left: 0;
      bottom: 0;
    }
    .hide {
      display: none;
    }
    .fixed-wrap{
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      z-index: 9999;
    }
    .fixed-video-wrap{
      background-color: #191919;
    }
    
  </style>
</head>
<body>
  <div class="video_wrap" >
    <?php if(stristr($_SERVER['HTTP_USER_AGENT'],'Android')) { ?>
      
    <?php } else { ?>
      <video playsinline webkit-playsinline id="videoInfoId"></video>
    <?php } ?>
    <img class="poster" src="img/thumb.jpg" alt="">
    <!-- 播放、暂停的按钮 -->
    <div class="video-btn-wrap" id="videoRightBtn">
      <a id="playVideoBtn"><img class="video-btn" src="img/video-play.svg"></a>
      <a id="stopVideoBtn" class="hide"><img class="video-btn" src="img/video-stop.svg"></a>
    </div>
    <!-- 进度条 -->
    <div id="videoPercentageId" class="video-percentage-bar"></div>
  </div>
  <div class="fixed-wrap fixed-video-wrap hide" id="J_android_video_wrap">
    <video id="videoInfoId"></video>
  </div>
</body>
<script src="http://cdn.static.runoob.com/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
$(function(){
  var windowW = headWidth = $(window).innerWidth(),
    videoW,
    videoH,
    videoShowW = 1,
    videoShowH = 1,
    videoMarginLeft = -1,
    videoMarginTop = -1,
    videoPercentage = 0;

  // 视频的信息  一般从后台获取赋值，这里是一个例子，值是定值
  var videoInfo = {
    width: '540',    //视频的宽度
    height: '303',   //视频的高度
    duration: '60000',  //视频的长度
    thumb: 'img/thumb.jpg',  //未播放时的图片
    video: 'video.mp4'   //视频的地址
  }

  //根据屏幕宽度给包裹视频的外标签赋值
  $('.video_wrap').css({
      'width': headWidth + 'px',
      'height': headWidth + 'px'
  });
  // video上封面图计算高度
  $('.video_wrap .poster').css('height', headWidth + 'px');

  // 转成number类型的数字
  videoW = videoInfo.width - 0;
  videoH = videoInfo.height - 0;

  if (videoW > videoH) {  // 宽大于高，横向
    videoShowH = windowW;
    videoShowW = Math.round(videoW * videoShowH / videoH);
  } else {
    videoShowW = windowW;
    videoShowH = Math.round(videoH * videoShowW / videoW);
  }
  //居中
  videoMarginLeft = Math.round((windowW - videoShowW) / 2);
  videoMarginTop = Math.round((windowW - videoShowH) / 2);


  $('#videoInfoId').attr('src', videoInfo.video);
  var isAndroid = navigator.userAgent.match('Android');   //判断是否是安卓系统
  
  function playVideo() {  
    var tmpWindowW = $(window).width(),
        tmpWindowH = $(window).height();
    if(isAndroid){
      $('#J_android_video_wrap').show();
      $('#videoInfoId').attr('src', videoInfo.video);
      $('#videoInfoId').css({
        'width': tmpWindowW + 'px',
        'height': tmpWindowH + 'px',
        'margin-left': '0px',
        'margin-top': '0px'
      });
    }else{
      $('#videoInfoId').css({
        'width': videoShowW + 'px',
        'height': videoShowH + 'px',
        'margin-left': videoMarginLeft + 'px',
        'margin-top': videoMarginTop + 'px'
      });
    }
    $('.poster').addClass('hide');
    $('#playVideoBtn').addClass('hide');
    $('#stopVideoBtn').removeClass('hide');
    $('#videoInfoId').get(0).play();
  }

  function stopVideo() {  
    $('#J_android_video_wrap').hide();
    $('#videoInfoId').css({
      'width': '1px',
      'height': '1px',
      'margin-left': '-1px',
      'margin-top': '-1px'
    });
    $('.poster').removeClass('hide');
    $('#playVideoBtn').removeClass('hide');
    $('#stopVideoBtn').addClass('hide');
    $('#videoInfoId').get(0).play();
    $('#videoInfoId').get(0).pause();
  }

  $('#videoInfoId').on('durationchange', function(data) {
    if (videoInfo.duration) {   // 后端duration单位为毫秒，currentTime和duration单位为秒
      videoPercentage = data.target.currentTime * 100000 / videoInfo.duration;
      $('#videoPercentageId').css('width', videoPercentage + '%');
    } else {
      videoPercentage = data.target.currentTime * 100 / data.target.duration;
      $('#videoPercentageId').css('width', videoPercentage + '%');
    }
  });
  
  // 当媒介长度改变时运行的脚本当播放位置改变时（比如当用户快进到媒介中一个不同的位置时）运行的脚本
  $('#videoInfoId').on('timeupdate', function(data) {
    if (videoInfo.duration) {   // 后端duration单位为毫秒，currentTime和duration单位为秒
      videoPercentage = data.target.currentTime * 100000 / videoInfo.duration;
      $('#videoPercentageId').css('width', videoPercentage + '%');
      if (data.target.currentTime * 1000 >= videoInfo.duration) {
        stopVideo();
        data.target.currentTime = 0;
      }
    } else {
      videoPercentage = data.target.currentTime * 100 / data.target.duration;
      $('#videoPercentageId').css('width', videoPercentage + '%');
    }
  });

  //播放视频
  $('#playVideoBtn').click(function(){
    $('#videoPercentageId').removeClass('hide');
    playVideo();
  })

  //暂停视频
  $('#stopVideoBtn').click(function(){
    $('#videoPercentageId').addClass('hide');
    stopVideo();
  })
  //视屏播放结束
  $('#videoInfoId').on('ended', function() {
    stopVideo();
  });

})
</script>
</html>