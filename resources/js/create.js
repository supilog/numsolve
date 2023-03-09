$(function () {
    // 初期処理
    const ns_init = function(){
        // クリアフラグ
        console.log($("#ns_clear").data('clearFlg'));

        let ns_data = get_ns_data();
        const json = JSON.stringify(ns_data, undefined, 1);
        sessionStorage.setItem('ns.data',json);
        ns_view();
    };
    // ローカルストレージからns.data配列を取得
    const get_ns_data = function(){
        const ns_data = sessionStorage.getItem('ns.data');
        if(ns_data === null) {
            var ret = Array(81);
            ret.fill(0);
            return ret;
        }else {
            return JSON.parse(sessionStorage.getItem('ns.data'));
        }
    };
    // ローカルストレージにindexを保存
    const ns_save_index = function(index){
        sessionStorage.setItem('ns.index', index);
    };
    // ローカルストレージにindexを保存
    const ns_remove_index = function(){
        sessionStorage.removeItem('ns.index');
    };
    // ローカルストレージに値を保存
    const ns_save_value = function(index, value){
        let ns_data = get_ns_data();      
        ns_data[index] = value;
        const json = JSON.stringify(ns_data, undefined, 1);
        sessionStorage.setItem('ns.data', json);
    };
    // ローカルストレージns.dataから表示を再生成する
    const ns_view = function (){
        let ns_data = get_ns_data();      
        // console.log(ns_data);
        for (let i = 0; i < 81; i++) {
            const targetValue = (ns_data[i] === null || ns_data[i] === 0) ? '&nbsp;' : ns_data[i];
            $("#ns-box-cell-" + i).html(targetValue);
        }
    };
    const ns_clear = function(){
        sessionStorage.clear();
        ns_view();
    }
    
    // セル押下時
    $(".ns-box-cell").click(function () {
        ns_save_index($(this).data('index'));
    });
    // モーダル数値押下時
    $("#numInputModal .modal-btn").click(function () {
        const index = sessionStorage.getItem('ns.index');
        ns_save_value(index, $(this).data('number'));
        ns_remove_index();
        ns_view();
    });
    // 「とうろくする」ボタン押下時
    $("#storeBtn").click(function () {
        // 全セルの数値を取得
        const ns_data = get_ns_data();
        $("#ns-data").val(JSON.stringify(ns_data, undefined, 1));
        $("#ns-store").submit();
    });
    $("#clearBtn").click(function(){
        ns_clear();
    });

    // 初期動作
    ns_init();
});