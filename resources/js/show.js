$(function () {
    // 初期処理
    const ns_init = function () {
        ns_view();
    };
    // #ns-data値からビューを生成する
    const ns_view = function () {
        const ns_data = $("#ns-data").data('json');
        for (let i = 0; i < 81; i++) {
            const targetValue = (ns_data[i] === null || ns_data[i] === 0) ? '&nbsp;' : ns_data[i];
            $("#ns-box-cell-" + i).html(targetValue);
        }
    };

    $("#solveBtn").click(function () {
        const postData = {
            'key': $("#ns-data").data('key')
        };
        $.ajax({
            type: "POST",
            url: '/solve',
            data: JSON.stringify(postData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).done(function (data) {
            // 入力
            const ns_data_answer = data.answer.data;
            if(ns_data_answer.length == 81){
                for (let i = 0; i < 81; i++) {
                    $("#ns-box-cell-" + i).html(ns_data_answer[i]);
                }
            }
            // CSS(問題)
            const ns_data_origin = data.question.data;
            for (let i = 0; i < 81; i++) {
                if(parseInt(ns_data_origin[i]) > 0){
                    $("#ns-box-cell-" + i).addClass('text-red-700');
                }else{
                    $("#ns-box-cell-" + i).addClass('text-xl');
                }
            }
        }).fail(function () {
            console.log('fail');
        });
    });

    ns_init();
});
