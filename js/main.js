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
$("#upload").on("click", function () {
    if ($("#file_select").prop("files").length === 0) {
        alert("ファイルを選択してください！");
        return;
    }
});

// カラム数を把握
const count_column = Object.keys(account_item_array[0]).length - 6;
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
                                    <tr><th colspan="${count_column}">資産の部</th></tr>
                                    <tr id="assets"><th class="one_space">資産合計</th></tr>
                                    <tr><th colspan="${count_column}">負債の部</th></tr>
                                    <tr><th colspan="${count_column}" class="one_space">流動負債</th></tr>
                                    <tr id="current_liabilities"><th class="two_space">流動負債合計</th></tr>
                                    <tr><th colspan="${count_column}" class="one_space">固定負債</th></tr>
                                    <tr id="non_current_liabilities"><th class="two_space">固定負債合計</th></tr>
                                    <tr id="liabilities"><th class="one_space">負債合計</th></tr>
                                    <tr><th colspan="${count_column}">純資産の部</th></tr>
                                    <tr><th colspan="${count_column}" class="one_space">株主資本</th></tr>
                                    <tr id="common_stock"><th colspan="${count_column}" class="two_space">資本剰余金</th></tr>
                                    <tr id="apic"><th class="three_space">資本剰余金合計</th></tr>
                                    <tr><th colspan="${count_column}" class="two_space">利益剰余金</th></tr>
                                    <tr id="profit_reserve"><th colspan="${count_column}" class="three_space">その他利益剰余金</th></tr>
                                    <tr><td class="four_space">繰越利益剰余金</td></tr>
                                    <tr id="other_retained_earnings"><th class="four_space">その他利益剰余金合計</th></tr>
                                    <tr id="retained_earnings"><th class="three_space">利益剰余金合計</th></tr>
                                    <tr id="shareholders_equity"><th class="two_space">株主資本合計</th></tr>
                                    <tr><th colspan="${count_column}" class="one_space">評価・換算差額等</th></tr>
                                    <tr id="vatd"><th class="two_space">評価・換算差額等合計</th></tr>
                                    <tr id="stock_acquisition_right"><th class="one_space">新株予約権</th></tr>
                                    <tr id="equity"><th class="one_space">純資産合計</th></tr>
                                    <tr id="liabilities_equity"><th class="one_space">負債純資産合計</th></tr>
                                </tbody>
                            </table>`,
`<table id="pl_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text_center">借方金額</th>
                                        <th class="text_center">貸方金額</th>
                                        <th class="text_center horizontal_total">合計額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="revenue"><th>売上高</th></tr>
                                    <tr id="cogs"><th>売上原価</th></tr>
                                    <tr id="gross_profit"><th>売上総利益</th></tr>
                                    <tr><th colspan="${count_column}">販売費及び一般管理費</th></tr>
                                    <tr id="sga"><th class="one_space">販売費及び一般管理費合計</th></tr>
                                    <tr id="operating_income"><th>営業利益</th></tr>
                                    <tr><th colspan="${count_column}">営業外収益</th></tr>
                                    <tr id="non_operating_revenue"><th class="one_space">営業外収益合計</th></tr>
                                    <tr><th colspan="${count_column}">営業外費用</th></tr>
                                    <tr id="non_operating_expenses"><th class="one_space">営業外費用合計</th></tr>
                                    <tr id="ordinary_profit"><th>経常利益</th></tr>
                                    <tr><th colspan="${count_column}">特別利益</th></tr>
                                    <tr id="special_profits"><th class="one_space">特別利益合計</th></tr>
                                    <tr><th colspan="${count_column}">特別損失</th></tr>
                                    <tr id="special_losses"><th class="one_space">特別損失合計</th></tr>
                                    <tr id="income_before_tax"><th>税引前当期純利益</th></tr>
                                    <tr id="corporate_tax"><th>法人税、住民税及び事業税</th></tr>
                                    <tr id="tax"><th>法人税等合計</th></tr>
                                    <tr id="net_income"><th>当期純利益</th></tr>
                                </tbody>
                            </table>`];

const total_content = [{ content: "流動資産", debit_sum: 0, credit_sum: 0, start: 1110000, end: 1120000, indicator: 1, id: "current_assets", class: "one_space", total_class: "two_space" },
{ content: "有形固定資産", debit_sum: 0, credit_sum: 0, start: 1121000, end: 1122000, indicator: 1, id: "ppae", class: "two_space", total_class: "three_space" },
{ content: "無形固定資産", debit_sum: 0, credit_sum: 0, start: 1122000, end: 1123000, indicator: 1, id: "intangible_assets", class: "two_space", total_class: "three_space" },
{ content: "投資その他の資産", debit_sum: 0, credit_sum: 0, start: 1123000, end: 1124000, indicator: 1, id: "isaona", class: "two_space", total_class: "three_space" },
{ content: "繰延資産", debit_sum: 0, credit_sum: 0, start: 1124000, end: 1125000, indicator: 1, id: "deferred_assets", class: "two_space", total_class: "three_space" },
{ content: "固定資産", debit_sum: 0, credit_sum: 0, start: 1120000, end: 1130000, indicator: 1, id: "non_current_assets", class: "one_space", total_class: "two_space" },
{ content: "資産合計", debit_sum: 0, credit_sum: 0, start: 1100000, end: 1200000, indicator: 1 },
{ content: "流動負債合計", debit_sum: 0, credit_sum: 0, start: 1210000, end: 1220000, indicator: -1 },
{ content: "固定負債合計", debit_sum: 0, credit_sum: 0, start: 1220000, end: 1230000, indicator: -1 },
{ content: "負債合計", debit_sum: 0, credit_sum: 0, start: 1200000, end: 1300000, indicator: -1 },
{ content: "資本剰余金合計", debit_sum: 0, credit_sum: 0, start: 1312000, end: 1313000, indicator: -1 },
{ content: "その他利益剰余金合計", debit_sum: 0, credit_sum: 0, start: 1313200, end: 1313300, indicator: -1 },
{ content: "利益剰余金合計", debit_sum: 0, credit_sum: 0, start: 1313000, end: 1314000, indicator: -1 },
{ content: "株主資本合計", debit_sum: 0, credit_sum: 0, start: 1310000, end: 1320000, indicator: -1 },
{ content: "評価・換算差額等合計", debit_sum: 0, credit_sum: 0, start: 1320000, end: 1330000, indicator: -1 },
{ content: "新株予約権", debit_sum: 0, credit_sum: 0, start: 1330000, end: 1340000, indicator: -1 },
{ content: "純資産合計", debit_sum: 0, credit_sum: 0, start: 1300000, end: 1400000, indicator: -1 },
{ content: "負債純資産合計", debit_sum: 0, credit_sum: 0, start: 1200000, end: 1400000, indicator: -1 },
{ content: "売上高", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2200000, indicator: -1 },
{ content: "売上値引及び戻り高", debit_sum: 0, credit_sum: 0, start: 2200000, end: 2300000, indicator: -1 },
{ content: "売上原価", debit_sum: 0, credit_sum: 0, start: 2300000, end: 2400000, indicator: 1 },
{ content: "売上総利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2400000, indicator: -1 },
{ content: "販売費及び一般管理費合計", debit_sum: 0, credit_sum: 0, start: 2400000, end: 2500000, indicator: 1 },
{ content: "営業利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2500000, indicator: -1 },
{ content: "営業外収益合計", debit_sum: 0, credit_sum: 0, start: 2500000, end: 2600000, indicator: -1 },
{ content: "営業外費用合計", debit_sum: 0, credit_sum: 0, start: 2600000, end: 2700000, indicator: 1 },
{ content: "経常利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2700000, indicator: -1 },
{ content: "特別利益合計", debit_sum: 0, credit_sum: 0, start: 2700000, end: 2800000, indicator: -1 },
{ content: "特別損失合計", debit_sum: 0, credit_sum: 0, start: 2800000, end: 2900000, indicator: 1 },
{ content: "税引前当期純利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2900000, indicator: -1 },
{ content: "法人税、住民税及び事業税", debit_sum: 0, credit_sum: 0, start: 2910000, end: 2920000, indicator: 1 },
{ content: "法人税等合計", debit_sum: 0, credit_sum: 0, start: 2900000, end: 2930000, indicator: 1 },
{ content: "当期純利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2930000, indicator: -1 }
];

// 試算表の表示準備
$("#output").append(disclosure_array);

// 各区分の合計を計算して数値を格納
account_item_array.forEach(element => {
    for (let i = 0; i < total_content.length; i++) {
        // 該当する区分の場合集計
        if (element.account_id > total_content[i].start && element.account_id < total_content[i].end) {
            total_content[i].debit_sum += element.debit_sum;
            total_content[i].credit_sum += element.credit_sum;
        }
    }
});
console.log(total_content);

// 各区分に勘定科目が存在しているか確認し存在していたら区分を作成する
const append_array = [{ id: "assets", num: [0, 5] }, // 資産区分内
{ id: "non_current_assets", num: [1, 2, 3, 4] }]; // 固定資産区分内
append_array.forEach(element => {
    for (let i = 0; i < element.num.length; i++) {
        if (total_content[element.num[i]].debit_sum != 0 || total_content[element.num[i]].credit_sum != 0) {
            $("#" + element.id).before(`<tr>
        <th colspan="${count_column}" class="${total_content[element.num[i]].class}">${total_content[element.num[i]].content}</th>
        </tr>
        <tr id="${total_content[element.num[i]].id}">
        <th class="${total_content[element.num[i]].total_class}">${total_content[element.num[i]].content + "合計"}</th>
        <td class="right_justified">${total_content[element.num[i]].debit_sum.toLocaleString()}</td> // コンマを付ける
        <td class="right_justified">${total_content[element.num[i]].credit_sum.toLocaleString()}</td>
        <td class="right_justified horizontal_total">${((total_content[element.num[i]].debit_sum - total_content[element.num[i]].credit_sum) * total_content[element.num[i]].indicator).toLocaleString()}</td>
        </tr>`);
        }
    }
});

// 試算表を作る
account_item_array.forEach(element => {
    // 流動資産
    if (element.account_id > 1110000 && element.account_id < 1120000) {
        $("#current_assets").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 有形固定資産
    if (element.account_id > 1121000 && element.account_id < 1122000) {
        $("#ppae").before(`
            <tr>
            <td class="three_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 無形固定資産
    if (element.account_id > 1122000 && element.account_id < 1123000) {
        $("#intangible_assets").before(`
            <tr>
            <td class="three_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 投資その他の資産
    if (element.account_id > 1123000 && element.account_id < 1124000) {
        $("#isaona").before(`
            <tr>
            <td class="three_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 繰延資産
    if (element.account_id > 1124000 && element.account_id < 1125000) {
        $("#deferred_assets").before(`
            <tr>
            <td class="three_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 流動負債
    if (element.account_id > 1210000 && element.account_id < 1220000) {
        $("#current_liabilities").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 固定負債
    if (element.account_id > 1220000 && element.account_id < 1230000) {
        $("#non_current_liabilities").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 資本金
    if (element.account_id > 1311000 && element.account_id < 1312000) {
        $("#common_stock").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 資本剰余金
    if (element.account_id > 1312000 && element.account_id < 1313000) {
        $("#apic").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 利益剰余金
    if (element.account_id > 1313100 && element.account_id < 1313200) {
        $("#profit_reserve").before(`
            <tr>
            <td class="three_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // その他利益剰余金
    if (element.account_id > 1313200 && element.account_id < 1313300) {
        $("#other_retained_earnings").before(`
            <tr>
            <td class="four_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 自己株式
    if (element.account_id > 1314000 && element.account_id < 1315000) {
        $("#shareholders_equity").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 評価・換算差額等
    if (element.account_id > 1320000 && element.account_id < 1330000) {
        $("#vatd").before(`
            <tr>
            <td class="two_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
    // 非支配株主持分
    if (element.account_id > 1340000 && element.account_id < 1350000) {
        $("#equity").before(`
            <tr>
            <td class="one_space">${element.account_item}</td>
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
            </tr>`);
        return;
    }
});

// ボタンを押したときにその他の記載を隠して試算表を表示
$("#submit").on("click", function () {
    $("#toggle").fadeToggle();
});