$(document).ready(function () {
    $(document).on('click', '.demo .settings', function (e) {
        e.preventDefault();
        if ($(this).parent().attr('style') === 'left: 0px;') {
            $(this).parent().animate({ 'left': '-200px' });
        }
        else {
            $(this).parent().animate({ 'left': '0' });
        }
    });

    if ($.cookie('color')) {
        $('#color').attr('href', 'Theme/css/color/' + $.cookie('color'));
    }
    $(document).on('click', '.demo .color',function (e) {
        e.preventDefault();
        $.cookie('color', $(this).data('color'), { expires: 7, path: '/' });
        $('#color').attr('href', 'Theme/css/color/' + $(this).data('color'));
    });

    if ($.cookie('box')) {
        $('html').addClass($.cookie('box'));
    }
    $(document).on('click', '.demo .boxed', function (e) {
        e.preventDefault();
        $.cookie('box', $(this).data('box'), { expires: 7, path: '/' });
        $('html').removeAttr('class').addClass($(this).data('box'));
        window.location.href = 'index.html';
    });

    if ($.cookie('background')) {
        if ($.cookie('box')) {
            $('body').css({ 'background-image': 'url(Theme/img/bg/' + $.cookie('background') + ')' });
        }
    }
    $(document).on('click', '.demo .background', function (e) {
        e.preventDefault();
        if ($.cookie('box')) {
            $.cookie('background', $(this).data('background'), { expires: 7, path: '/' });
            $('body').css({ 'background-image': 'url(Theme/img/bg/' + $(this).data('background') + ')' });
        }
    });

    $(document).on('click', '.demo .reset', function (e) {
        e.preventDefault();
        $('#color').attr('href', 'Theme/css/color/Default.css');
        $('html').removeClass('boxed');
        $('body').removeAttr('style');
        $.removeCookie('color', { path: '/' });
        $.removeCookie('box', { path: '/' });
        $.removeCookie('background', { path: '/' });
    });

    var html = '' +
        '<div class="demo">' +
        '<a href="#" class="settings">' +
        '<i class="fa fa-cog fa-spin"></i>' +
        '</a>' +
        '<h5>Color</h5>' +
        '<a href="#" class="color" data-color="Default.css" style="background-color:#ffa700"></a>' +
        '<a href="#" class="color" data-color="Purple.css" style="background-color:#8e2a7e"></a>' +
        '<a href="#" class="color" data-color="turquoise.css" style="background-color:#00b4d9"></a>' +
        '<a href="#" class="color" data-color="red.css" style="background-color:#e53f51"></a>' +
        '<a href="#" class="color" data-color="green.css" style="background-color:#029A83"></a>' +
        '<a href="#" class="color" data-color="pink.css" style="background-color:#ef3e96"></a>' +
        '<a href="#" class="color" data-color="blue.css" style="background-color:#3957b7"></a>' +
        '<a href="#" class="color" data-color="orange.css" style="background-color:#ff423a"></a>' +
        '<a href="#" class="color" data-color="green2.css" style="background-color:#87D37C"></a>' +
        '<a href="#" class="color" data-color="grey.css" style="background-color:#ABB7B7"></a>' +
        '<hr />' +
        '<h5>Box</h5>' +
        '<a href="#" class="boxed btn btn-sm btn-primary m-r-5" data-box="wide">Wide</a>' +
        '<a href="#" class="boxed btn btn-sm btn-primary m-l-5" data-box="boxed">Boxed</a>' +
        '<hr />' +
        '<h5>Background</h5>' +
        '<a href="#" class="background" data-background="bedge_grunge.png" style="background-image:url(Theme/img/bg/bedge_grunge.png)"></a>' +
        '<a href="#" class="background" data-background="brushed_alu.png" style="background-image:url(Theme/img/bg/brushed_alu.png)"></a>' +
        '<a href="#" class="background" data-background="crisp_paper_ruffles.png" style="background-image:url(Theme/img/bg/crisp_paper_ruffles.png)"></a>' +
        '<a href="#" class="background" data-background="diamond_upholstery.png" style="background-image:url(Theme/img/bg/diamond_upholstery.png)"></a>' +
        '<a href="#" class="background" data-background="light_toast.png" style="background-image:url(Theme/img/bg/light_toast.png)"></a>' +
        '<a href="#" class="background" data-background="linedpaper.png" style="background-image:url(Theme/img/bg/linedpaper.png)"></a>' +
        '<a href="#" class="background" data-background="navy_blue.png" style="background-image:url(Theme/img/bg/navy_blue.png)"></a>' +
        '<a href="#" class="background" data-background="noise_pattern_with_crosslines.png" style="background-image:url(Theme/img/bg/noise_pattern_with_crosslines.png)"></a>' +
        '<a href="#" class="background" data-background="purty_wood.png" style="background-image:url(Theme/img/bg/purty_wood.png)"></a>' +
        '<a href="#" class="background" data-background="retina_wood.png" style="background-image:url(Theme/img/bg/retina_wood.png)"></a>' +
        '<a href="#" class="background" data-background="sativa.png" style="background-image:url(Theme/img/bg/sativa.png)"></a>' +
        '<a href="#" class="background" data-background="shattered.png" style="background-image:url(Theme/img/bg/shattered.png)"></a>' +
        '<a href="#" class="background" data-background="squared_metal.png" style="background-image:url(Theme/img/bg/squared_metal.png)"></a>' +
        '<a href="#" class="background" data-background="stardust.png" style="background-image:url(Theme/img/bg/stardust.png)"></a>' +
        '<a href="#" class="background" data-background="subtle_stripes.png" style="background-image:url(Theme/img/bg/subtle_stripes.png)"></a>' +
        '<a href="#" class="background" data-background="textured_stripes.png" style="background-image:url(Theme/img/bg/textured_stripes.png)"></a>' +
        '<a href="#" class="background" data-background="tileable_wood_texture.png" style="background-image:url(Theme/img/bg/tileable_wood_texture.png)"></a>' +
        '<a href="#" class="background" data-background="vichy.png" style="background-image:url(Theme/img/bg/vichy.png)"></a>' +
        '<a href="#" class="background" data-background="washi.png" style="background-image:url(Theme/img/bg/washi.png)"></a>' +
        '<hr />' +
        '<a href="#" class="reset btn btn-sm btn-info p-l-30 p-r-30">Reset</a>' +
        '</div>';
    $('body').append(html);
});