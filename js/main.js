//文字列の類似度チェック（レーベンシュタイン距離）
function levenshteinDistance(str1, str2) {
    let cost,
        distance = [];

    for (let i = 0; i <= str1.length; i++) {
        distance[i] = [i];
    }
    if (distance.length > 0) {
        for (let j = 0; j <= str2.length; j++) {
            distance[0][j] = j;
        }
        for (let i = 1; i <= str1.length; i++) {
            for (let j = 1; j <= str2.length; j++) {
                cost = str1.charCodeAt(i - 1) == str2.charCodeAt(j - 1) ? 0 : 1;
                distance[i][j] = Math.min(distance[i - 1][j] + 1, distance[i][j - 1] + 1, distance[i - 1][j - 1] + cost);
            }
        }
        return distance[str1.length][str2.length];
    }
}
console.log(levenshteinDistance("支払報酬料", "支払報酬")); // => 1
console.log(levenshteinDistance("支払報酬料", "支払報酬料")); // => 0
console.log(levenshteinDistance("支払報酬料", "役員報酬")); // => 3
console.log(levenshteinDistance("支払報酬料", "受取報酬料")); // => 2
console.log(levenshteinDistance("支払報酬料", "減価償却費")); // => 5

const fsAccountArray = [];
const tbAccountArray = [];

// 勘定科目名だけを集めた配列を作成
bsAccountItems.forEach(element => {
    fsAccountArray.push(element.fsAccount);
    tbAccountArray.push(element.tbAccount);
});
plAccountItems.forEach(element => {
    fsAccountArray.push(element.fsAccount);
    tbAccountArray.push(element.tbAccount);
});

// 借方用
function debitMoveNumber(actualArray, accontent) {
    // 決算書表示名との一致を確認
    if (Object.keys(actualArray).includes(accontent.fsAccount)) {
        // 用意した勘定科目一覧に数値を代入する
        accontent.deamount = actualArray[accontent.fsAccount];
        // オブジェクト配列に格納したら重複しないよう削除
        delete actualArray[accontent.fsAccount];

        // 勘定科目名との一致を確認
    } else if (Object.keys(actualArray).includes(accontent.tbAccount)) {
        accontent.deamount = actualArray[accontent.tbAccount];
        delete actualArray[accontent.tbAccount];
    } else {
        Object.keys(actualArray).forEach(elementItem => {
            // 文字列が類似しておりかつ標準の勘定科目名に含まれていない場合、類似の勘定科目として登録する
            if ((levenshteinDistance(accontent.fsAccount, elementItem) < 2 ||
                levenshteinDistance(accontent.tbAccount, elementItem) < 2) &&
                (fsAccountArray.indexOf(elementItem) === -1 &&
                    tbAccountArray.indexOf(elementItem) === -1)) {
                console.log(accontent.fsAccount, elementItem, actualArray[elementItem]);
                accontent.deamount = actualArray[elementItem]; // 「.」形式ではエラーとなる
                delete actualArray[elementItem];
            }
        });
    }
}

// 貸方用
function creditMoveNumber(actualArray, accontent) {
    // 決算書表示名との一致を確認
    if (Object.keys(actualArray).includes(accontent.fsAccount)) {
        // 用意した勘定科目一覧に数値を代入する
        accontent.cramount = actualArray[accontent.fsAccount];
        // オブジェクト配列に格納したら重複しないよう削除
        delete actualArray[accontent.fsAccount];

        // 勘定科目名との一致を確認
    } else if (Object.keys(actualArray).includes(accontent.tbAccount)) {
        accontent.cramount = actualArray[accontent.tbAccount];
        delete actualArray[accontent.tbAccount];
    } else {
        Object.keys(actualArray).forEach(elementItem => {
            // 文字列が類似しておりかつ標準の勘定科目名に含まれていない場合、類似の勘定科目として登録する
            if ((levenshteinDistance(accontent.fsAccount, elementItem) < 2 ||
                levenshteinDistance(accontent.tbAccount, elementItem) < 2) &&
                (fsAccountArray.indexOf(elementItem) === -1 &&
                    tbAccountArray.indexOf(elementItem) === -1)) {
                console.log(accontent.fsAccount, elementItem, actualArray[elementItem]);
                accontent.cramount = actualArray[elementItem]; // 「.」形式ではエラーとなる
                delete actualArray[elementItem];
            }
        });
    }
}
console.log(debitArray, creditArray)

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

// BS初期値に集計数値を代入
bsAccountItems.forEach(element => {
    debitMoveNumber(debitArray, element);
    creditMoveNumber(creditArray, element);
});

// PL初期値に集計数値を代入
plAccountItems.forEach(element => {
    debitMoveNumber(debitArray, element);
    creditMoveNumber(creditArray, element);
});
console.log(debitArray, creditArray) // 全ての勘定科目の数字を移すことができたか確認

// 数値表示用
const disclosureArray = [`<tr><th>決算書表示名</th><th class="tb_debit">借方金額</th><th class="tb_credit">貸方金額</th><th class="tb_horizontal_total">合計額</th></tr>`];

// BSを作る
bsAccountItems.forEach(element => {
    if (element.deamount != 0 || element.cramount != 0) {
        disclosureArray.push(`<tr>
            <th class="left_justified">${element.fsAccount}</th>
            <td class="right_justified tb_debit">${element.deamount.toLocaleString()}</td> // コンマを付ける
            <td class="right_justified tb_credit">${element.cramount.toLocaleString()}</td>
            <td class="right_justified tb_horizontal_total">${((element.deamount - element.cramount) * element.indicator).toLocaleString()}</td>
            </tr>
        }`);
    }
});

// PLを作る
plAccountItems.forEach(element => {
    if (element.deamount != 0 || element.cramount != 0) {
        disclosureArray.push(`<tr>
            <th class="left_justified">${element.fsAccount}</th>
            <td class="right_justified tb_debit">${element.deamount.toLocaleString()}</td>
            <td class="right_justified tb_credit">${element.cramount.toLocaleString()}</td>
            <td class="right_justified tb_horizontal_total">${((element.deamount - element.cramount) * element.indicator).toLocaleString()}</td>
            </tr>
        }`);
    }
});

if (disclosureArray.length > 1) {
    $("#submit").show();
    // 試算表の表示準備
    $("#output").append("<table></table>");
    $("table").append(disclosureArray);
}

// ボタンを押したときにその他の記載を隠して試算表を表示
$("#submit").on("click", function () {
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