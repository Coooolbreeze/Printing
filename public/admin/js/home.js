var base = window.base;
base.getLocalStorage('token') || (window.location.href = 'login.html');

base.getData({
    url: '/users/self',
    tokenFlag: true,
    success: function (res) {
        console.log(res);
    }
});

layui.use(['element', 'layer'], function () {
    var element = layui.element;

    base.loadLocalHtml('product.html', '.layui-body');

    $('#product').on('click', function () {
        base.loadLocalHtml('product.html', '.layui-body');
    });

    $('#logout').on('click', function () {
        base.deleteLocalStorage('token');
        base.deleteLocalStorage('refresh_token');
        window.location.href = 'login.html';
    });
});