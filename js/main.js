// BS初期値に集計数値を代入
bsAccountItems.forEach(element => {
    // 借方修正配列
    // 決算書表示名との一致を確認
    if (Object.keys(debitArray).includes(element.fsAccount)) {
        element.deamount = debitArray[element.fsAccount];
        // オブジェクト配列に格納したら重複しないよう削除
        delete debitArray[element.fsAccount];

        // 勘定科目名との一致を確認
    } else if (Object.keys(debitArray).includes(element.tbAccount)) {
        element.deamount = debitArray[element.tbAccount];

    } else {
        console.log(element.fsAccount + "はない")
    }

    // 貸方集計配列
    // 決算書表示名との一致を確認
    if (Object.keys(creditArray).includes(element.fsAccount)) {
        element.cramount = creditArray[element.fsAccount];
        // オブジェクト配列に格納したら重複しないよう削除
        delete creditArray[element.fsAccount];

        // 勘定科目名との一致を確認
    } else if (Object.keys(creditArray).includes(element.tbAccount)) {
        element.cramount = creditArray[element.tbAccount];

    } else {
        console.log(element.fsAccount + "はないnai")
    }
});

// PL初期値に集計数値を代入
plAccountItems.forEach(element => {
    // 借方修正配列
    // 決算書表示名との一致を確認
    if (Object.keys(debitArray).includes(element.fsAccount)) {
        element.deamount = debitArray[element.fsAccount];
        // オブジェクト配列に格納したら重複しないよう削除
        delete debitArray[element.fsAccount];

        // 勘定科目名との一致を確認
    } else if (Object.keys(debitArray).includes(element.tbAccount)) {
        element.deamount = debitArray[element.tbAccount];
    }

    // 貸方集計配列
    // 決算書表示名との一致を確認
    if (Object.keys(creditArray).includes(element.fsAccount)) {
        element.cramount = creditArray[element.fsAccount];
        // オブジェクト配列に格納したら重複しないよう削除
        delete creditArray[element.fsAccount];

        // 勘定科目名との一致を確認
    } else if (Object.keys(creditArray).includes(element.tbAccount)) {
        element.cramount = creditArray[element.tbAccount];
    }
});

// 数値表示用
const disclosureArray = ["<tr><th>決算書表示名</th><th>借方金額</th><th>貸方金額</th><th>合計額</th></tr>"];

// BSを作る
bsAccountItems.forEach(element => {
    if (element.deamount != 0 || element.cramount != 0) {
        disclosureArray.push(`<tr>
            <th class="leftJustified">${element.fsAccount}</th>
            <td class="rightJustified">${element.deamount.toLocaleString()}</td>
            <td class="rightJustified">${element.cramount.toLocaleString()}</td>
            <td class="rightJustified">${((element.deamount - element.cramount) * element.indicator).toLocaleString()}</td>
            </tr>
        }`);
    }
});

// PLを作る
plAccountItems.forEach(element => {
    if (element.deamount != 0 || element.cramount != 0) {
        disclosureArray.push(`<tr>
            <th class="leftJustified">${element.fsAccount}</th>
            <td class="rightJustified">${element.deamount.toLocaleString()}</td>
            <td class="rightJustified">${element.cramount.toLocaleString()}</td>
            <td class="rightJustified">${((element.deamount - element.cramount) * element.indicator).toLocaleString()}</td>
            </tr>
        }`);
    }
});
console.log(disclosureArray.length);

if (disclosureArray.length > 1) {
    $("#submit").show();
    // 試算表の表示準備
    $("#output").append("<table></table>");
    $("table").append(disclosureArray);
}

// ボタンを押したときにその他の記載を隠して試算表を表示
$("#submit").on("click", function () {
    $("#file_upload").hide();
    $("#toggle").fadeToggle();
});

// output.phpに変数を送信
axios.post("output.php", JSON.stringify(bsAccountItems[0]), {
    headers: { "Content-Type": "application/json" }
})
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error("エラーが発生しました:", error);
    });