console.log(account_item_array)

// モーダル
$(function () {
    //開くボタンをクリックしたらモーダルを表示する
    $(".modal-open").on('click', function () {
        $(".modal-container").addClass("active");
        return false;
    });

    //閉じるボタンをクリックしたらモーダルを閉じる
    $(".modal-close").on("click", function () {
        $(".modal-container").removeClass("active");
    });

    //モーダルの外側をクリックしたらモーダルを閉じる
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".modal-body").length) {
            $(".modal-container").removeClass("active");
        }
    });
});

// ファイル名を検索
// 中身が変更された際実行
$("#file_select").change(function () {
    const file_name = $(this).prop("files")[0].name;
    $("#file_name_output").show();
    $("#file_name").text(file_name);
});

// アップロードファイル未選択時のアラート
$("#upload_btn").on("click", function () {
    if ($("#file_select").prop("files").length === 0) {
        alert("ファイルを選択してください！");
    }
});

// 数値表示用
const disclosure_array = [`<table  id="bs_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text_center">借方金額</th>
                                        <th class="text_center">貸方金額</th>
                                        <th class="text_center horizontal_total">合計額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><th>資産の部</th></tr>
                                    <tr><th>　流動資産</th></tr>
                                    <tr><th>　　流動資産合計</th></tr>
                                    <tr><th>　固定資産</th></tr>
                                    <tr><th>　　有形固定資産</th></tr>
                                    <tr><th>　　無形固定資産</th></tr>
                                    <tr><th>　　投資その他の資産</th></tr>
                                    <tr><th>　　繰延資産</th></tr>
                                    <tr><th>　　固定資産合計</th></tr>
                                    <tr><th>　資産合計</th></tr>
                                    <tr><th>負債の部</th></tr>
                                    <tr><th>　流動負債</th></tr>
                                    <tr><th>　　流動負債合計</th></tr>
                                    <tr><th>　固定負債</th></tr>
                                    <tr><th>　　固定負債合計</th></tr>
                                    <tr><th>　負債合計</th></tr>
                                    <tr><th>純資産の部</th></tr>
                                    <tr><th>　株主資本</th></tr>
                                    <tr><th>　　資本剰余金</th></tr>
                                    <tr><th>　　　資本剰余金合計</th></tr>
                                    <tr><th>　　利益剰余金</th></tr>
                                    <tr><td>　　　その他利益剰余金</td></tr>
                                    <tr><th>　　　利益剰余金合計</th></tr>
                                    <tr><th>　　株主資本合計</th></tr>
                                    <tr><th>　評価・換算差額等</th></tr>
                                    <tr><th>　　評価・換算差額等合計</th></tr>
                                    <tr><th>　純資産合計</th></tr>
                                    <tr><th>負債純資産合計</th></tr>
                                </tbody>
                            </table>`,
                            `<table>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text_center">借方金額</th>
                                        <th class="text_center">貸方金額</th>
                                        <th class="text_center horizontal_total">合計額</th>
                                    </tr>
                                </thead>
                                <tbody id="pl_table">
                                    <tr><th>売上高</th></tr>
                                    <tr><th>売上原価</th></tr>
                                    <tr><th>売上総利益</th></tr>
                                    <tr><th>販売費及び一般管理費</th></tr>
                                    <tr><th>　販売費及び一般管理費合計</th></tr>
                                    <tr><th>営業利益</th></tr>
                                    <tr><th>営業外収益</th></tr>
                                    <tr><th>　営業外収益合計</th></tr>
                                    <tr><th>営業外費用</th></tr>
                                    <tr><th>　営業外費用合計</th></tr>
                                    <tr><th>経常利益</th></tr>
                                    <tr><th>特別利益</th></tr>
                                    <tr><th>　特別利益合計</th></tr>
                                    <tr><th>特別損失</th></tr>
                                    <tr><th>　特別損失合計</th></tr>
                                    <tr><th>税引前当期純利益</th></tr>
                                    <tr><th>法人税、住民税及び事業税</th></tr>
                                    <tr><th>法人税等合計</th></tr>
                                    <tr><th>当期純利益</th></tr>
                                </tbody>
                            </table>`];

// 試算表の表示準備
$("#output").append(disclosure_array);

// BSを作る
// bs_account_items.forEach(element => {
//     if (element.debit_amount != 0 || element.credit_amount != 0) {
//         disclosure_array.push(`<tr>
//             <th>${element.fs_account}</th>
//             <td class="right_justified">${element.debit_amount.toLocaleString()}</td> // コンマを付ける
//             <td class="right_justified">${element.credit_amount.toLocaleString()}</td>
//             <td class="right_justified horizontal_total">${((element.debit_amount - element.credit_amount) * element.indicator).toLocaleString()}</td>
//             </tr>
//         }`);
//     }
// });

// // PLを作る
// pl_account_items.forEach(element => {
//     if (element.debit_amount != 0 || element.credit_amount != 0) {
//         disclosure_array.push(`<tr>
//             <th>${element.fs_account}</th>
//             <td class="right_justified tb_debit">${element.debit_amount.toLocaleString()}</td>
//             <td class="right_justified tb_credit">${element.credit_amount.toLocaleString()}</td>
//             <td class="right_justified tb_horizontal_total">${((element.debit_amount - element.credit_amount) * element.indicator).toLocaleString()}</td>
//             </tr>
//         }`);
//     }
// });

// ボタンを押したときにその他の記載を隠して試算表を表示
$("#submit").on("click", function () {
    $("#toggle").fadeToggle();
});