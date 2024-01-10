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

if (account_item_array !== 0) {
    // カラム数を把握
    const count_column = Object.keys(account_item_array[0]).length - 6;
    // 数値表示用
    const disclosure_array = [`<table  id="bs_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <td class="text_center">借方金額</td>
                                        <td class="text_center">貸方金額</td>
                                        <td class="text_center horizontal_total">合計額</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><th colspan="${count_column}">資産の部</th></tr>
                                    <tr id="assets"><th class="one_space">資産合計</th></tr>
                                    <tr><th colspan="${count_column}">負債の部</th></tr>
                                    <tr id="liabilities"><th class="one_space">負債合計</th></tr>
                                    <tr><th colspan="${count_column}">純資産の部</th></tr>
                                    <tr><th colspan="${count_column}" class="one_space">株主資本</th></tr>
                                    <tr id="common_stock"><th colspan="${count_column}" class="two_space">資本剰余金</th></tr>
                                    <tr id="apic"><th class="three_space">資本剰余金合計</th></tr>
                                    <tr><th colspan="${count_column}" class="two_space">利益剰余金</th></tr>
                                    <tr id="profit_reserve"><th colspan="${count_column}" class="three_space">その他利益剰余金</th></tr>
                                    <tr id="other_retained_earnings"><th class="four_space">その他利益剰余金合計</th></tr>
                                    <tr id="retained_earnings"><th class="three_space">利益剰余金合計</th></tr>
                                    <tr id="shareholders_equity"><th class="two_space">株主資本合計</th></tr>
                                    <tr id="equity"><th class="one_space">純資産合計</th></tr>
                                    <tr id="liabilities_equity"><th class="one_space">負債純資産合計</th></tr>
                                </tbody>
                            </table>`,
    `<table id="pl_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <td class="text_center">借方金額</td>
                                        <td class="text_center">貸方金額</td>
                                        <td class="text_center horizontal_total">合計額</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="revenue"><th>売上高</th></tr>
                                    <tr id="cogs"><th>売上原価</th></tr>
                                    <tr id="gross_profit"><th>売上総利益</th></tr>
                                    <tr><th colspan="${count_column}">販売費及び一般管理費</th></tr>
                                    <tr id="sga"><th class="two_space">販売費及び一般管理費合計</th></tr>
                                    <tr id="operating_income"><th>営業利益</th></tr>
                                    <tr><th colspan="${count_column}">営業外収益</th></tr>
                                    <tr id="non_operating_revenue"><th class="two_space">営業外収益合計</th></tr>
                                    <tr><th colspan="${count_column}">営業外費用</th></tr>
                                    <tr id="non_operating_expenses"><th class="two_space">営業外費用合計</th></tr>
                                    <tr id="ordinary_profit"><th>経常利益</th></tr>
                                    <tr><th colspan="${count_column}">特別利益</th></tr>
                                    <tr id="special_profits"><th class="two_space">特別利益合計</th></tr>
                                    <tr><th colspan="${count_column}">特別損失</th></tr>
                                    <tr id="special_losses"><th class="two_space">特別損失合計</th></tr>
                                    <tr id="income_before_tax"><th>税引前当期純利益</th></tr>
                                    <tr id="corporate_tax"><th>法人税、住民税及び事業税</th></tr>
                                    <tr id="net_tax"><th>法人税等合計</th></tr>
                                    <tr id="net_income"><th>当期純利益</th></tr>
                                </tbody>
                            </table>`];

    // 合計欄集計
    const total_content = [{ content: "流動資産", debit_sum: 0, credit_sum: 0, start: 1110000, end: 1120000, indicator: 1, id: "current_assets", class: "one_space", total_class: "two_space" },
    { content: "有形固定資産", debit_sum: 0, credit_sum: 0, start: 1121000, end: 1122000, indicator: 1, id: "ppae", class: "two_space", total_class: "three_space" },
    { content: "無形固定資産", debit_sum: 0, credit_sum: 0, start: 1122000, end: 1123000, indicator: 1, id: "intangible_assets", class: "two_space", total_class: "three_space" },
    { content: "投資その他の資産", debit_sum: 0, credit_sum: 0, start: 1123000, end: 1124000, indicator: 1, id: "isaona", class: "two_space", total_class: "three_space" },
    { content: "繰延資産", debit_sum: 0, credit_sum: 0, start: 1124000, end: 1125000, indicator: 1, id: "deferred_assets", class: "two_space", total_class: "three_space" },
    { content: "固定資産", debit_sum: 0, credit_sum: 0, start: 1120000, end: 1130000, indicator: 1, id: "non_current_assets", class: "one_space", total_class: "two_space" },
    { content: "資産合計", debit_sum: 0, credit_sum: 0, start: 1100000, end: 1200000, indicator: 1, id: "assets" },
    { content: "流動負債", debit_sum: 0, credit_sum: 0, start: 1210000, end: 1220000, indicator: -1, id: "current_liabilities", class: "one_space", total_class: "two_space" },
    { content: "固定負債", debit_sum: 0, credit_sum: 0, start: 1220000, end: 1230000, indicator: -1, id: "non_current_liabilities", class: "one_space", total_class: "two_space" },
    { content: "負債合計", debit_sum: 0, credit_sum: 0, start: 1200000, end: 1300000, indicator: -1, id: "liabilities" },
    { content: "資本剰余金合計", debit_sum: 0, credit_sum: 0, start: 1312000, end: 1313000, indicator: -1, id: "apic" },
    { content: "その他利益剰余金合計", debit_sum: 0, credit_sum: 0, start: 1313200, end: 1313300, indicator: -1, id: "other_retained_earnings", total_class: "four_space" },
    { content: "利益剰余金合計", debit_sum: 0, credit_sum: 0, start: 1313000, end: 1314000, indicator: -1, id: "retained_earnings" },
    { content: "株主資本合計", debit_sum: 0, credit_sum: 0, start: 1310000, end: 1320000, indicator: -1, id: "shareholders_equity" },
    { content: "評価・換算差額等", debit_sum: 0, credit_sum: 0, start: 1320000, end: 1330000, indicator: -1, id: "vatd", class: "one_space", total_class: "two_space" },
    { content: "新株予約権", debit_sum: 0, credit_sum: 0, start: 1330000, end: 1340000, indicator: -1, id: "stock_acquisition_right", class: "one_space", total_class: "two_space" },
    { content: "純資産合計", debit_sum: 0, credit_sum: 0, start: 1300000, end: 1400000, indicator: -1, id: "equity" },
    { content: "負債純資産合計", debit_sum: 0, credit_sum: 0, start: 1200000, end: 1400000, indicator: -1, id: "liabilities_equity" },
    { content: "売上高", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2200000, indicator: -1, id: "revenue" },
    { content: "売上原価", debit_sum: 0, credit_sum: 0, start: 2300000, end: 2400000, indicator: 1, id: "cogs" },
    { content: "売上総利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2400000, indicator: -1, id: "gross_profit" },
    { content: "販売費及び一般管理費合計", debit_sum: 0, credit_sum: 0, start: 2400000, end: 2500000, indicator: 1, id: "sga", total_class: "two_space" },
    { content: "営業利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2500000, indicator: -1, id: "operating_income" },
    { content: "営業外収益合計", debit_sum: 0, credit_sum: 0, start: 2500000, end: 2600000, indicator: -1, id: "non_operating_revenue", total_class: "two_space" },
    { content: "営業外費用合計", debit_sum: 0, credit_sum: 0, start: 2600000, end: 2700000, indicator: 1, id: "non_operating_expenses", total_class: "two_space" },
    { content: "経常利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2700000, indicator: -1, id: "ordinary_profit" },
    { content: "特別利益合計", debit_sum: 0, credit_sum: 0, start: 2700000, end: 2800000, indicator: -1, id: "special_profits", total_class: "two_space" },
    { content: "特別損失合計", debit_sum: 0, credit_sum: 0, start: 2800000, end: 2900000, indicator: 1, id: "special_losses", total_class: "two_space" },
    { content: "税引前当期純利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2900000, indicator: -1, id: "income_before_tax" },
    { content: "法人税、住民税及び事業税", debit_sum: 0, credit_sum: 0, start: 2910000, end: 2920000, indicator: 1, id: "corporate_tax" },
    { content: "法人税等合計", debit_sum: 0, credit_sum: 0, start: 2900000, end: 2930000, indicator: 1, id: "net_tax", total_class: "two_space" },
    { content: "当期純利益", debit_sum: 0, credit_sum: 0, start: 2100000, end: 2930000, indicator: -1, id: "net_income" }
    ];

    // 試算表の表示準備
    $("#output").append(disclosure_array);

    // 先に当期純利益のみ集計
    account_item_array.forEach(element => {
        // 該当する区分の場合集計
        if (element.account_id > total_content[total_content.length - 1].start && element.account_id < total_content[total_content.length - 1].end) {
            total_content[total_content.length - 1].debit_sum += element.debit_sum;
            total_content[total_content.length - 1].credit_sum += element.credit_sum;
        }
    });

    // 当期純利益を繰越利益剰余金とする
    for (let i = 0; i < account_item_array.length; i++) {
        if (account_item_array[i].account_id === "1313232") {
            account_item_array[i].debit_sum += total_content[total_content.length - 1].debit_sum;
            account_item_array[i].credit_sum += total_content[total_content.length - 1].credit_sum;
        }
    }

    // 各区分の合計を計算して数値を格納
    account_item_array.forEach(element => {
        for (let i = 0; i < total_content.length - 1; i++) {
            // 該当する区分の場合集計
            if (element.account_id > total_content[i].start && element.account_id < total_content[i].end) {
                total_content[i].debit_sum += element.debit_sum;
                total_content[i].credit_sum += element.credit_sum;
            }
        }
    });
    console.log(account_item_array);
    console.log(total_content);

    // 各区分に勘定科目が存在しているか確認し存在していたら区分を作成する
    const append_array = [{ id: "assets", num: [0, 5] }, // 資産区分内
    { id: "non_current_assets", num: [1, 2, 3, 4] }, // 固定資産区分内
    { id: "liabilities", num: [7, 8] }, // 負債区分内
    { id: "equity", num: [14, 15] }]; // 純資産区分内

    append_array.forEach(element => {
        for (let i = 0; i < element.num.length; i++) {
            if (total_content[element.num[i]].debit_sum != 0 || total_content[element.num[i]].credit_sum != 0) {
                $("#" + element.id).before(`<tr>
                <th colspan="${count_column}" class="${total_content[element.num[i]].class}">${total_content[element.num[i]].content}</th>
                </tr>
                <tr id="${total_content[element.num[i]].id}">
                <th class="${total_content[element.num[i]].total_class}">${total_content[element.num[i]].content + "合計"}</th>
                </tr>`);
            }
        }
    });

    // 集計した合計欄を表示する
    total_content.forEach(element => {
        let indicator = element.indicator;
        if (element.debit_sum === 0 && element.credit_sum === 0) {
            indicator = 1;
        }
        $("#" + element.id).append(`
            <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
            <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * indicator).toLocaleString()}</td>
            `);
    });

    // 勘定科目を追加する可能性がある配列番号
    const array_num = [0, 1, 2, 3, 4, 7, 8, 10, 11, 14, 21, 23, 24, 26, 27, 30];
    // 試算表を作る
    account_item_array.forEach(element => {
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
        // 売上値引及び戻り高
        if (element.account_id > 2200000 && element.account_id < 2300000) {
            $("#cogs").before(`
                <tr>
                <td class="three_space">${element.account_item}</td>
                <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
                <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
                <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
                </tr>`);
            return;
        }
        // 仕訳が存在する場合勘定科目を追加する
        for (let i = 0; i < array_num.length; i++) {
            if (element.account_id > total_content[array_num[i]].start && element.account_id < total_content[array_num[i]].end) {
                $("#" + total_content[array_num[i]].id).before(`
                    <tr>
                    <td class="${total_content[array_num[i]].total_class}">${element.account_item}</td>
                    <td class="right_justified">${element.debit_sum.toLocaleString()}</td> // コンマを付ける
                    <td class="right_justified">${element.credit_sum.toLocaleString()}</td>
                    <td class="right_justified horizontal_total">${((element.debit_sum - element.credit_sum) * element.indicator).toLocaleString()}</td>
                    </tr>`);
            }
        }
    });
}

// ボタンを押したときにその他の記載を隠して試算表を表示
$("#submit").on("click", function () {
    $("#toggle").fadeToggle();
});