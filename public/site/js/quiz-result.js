var quiz_answers_array = [];
var quiz_results_array = [];
var quiz_answered_questions = [];
var quiz_user_selected_answer = [];

var quiz_correct_count = 0;

var is_last_question_answered = false;

function get_quiz_answers(post_id) {
    var url = $('#url').val();
    $.ajax({
        type: "GET",
        dataType: 'json',
        data: { "post_id": post_id },
        url: url + '/' + 'get-quiz-answer-array',
        success: function (answer) {

            if (answer[0] == 1) {
                quiz_answers_array = answer[1];
                console.log(quiz_answers_array);
            }
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}
function get_quiz_results(post_id) {
    var url = $('#url').val();
    $.ajax({
        type: "GET",
        dataType: 'json',
        data: { "post_id": post_id },
        url: url + '/' + 'get-quiz-result-array',
        success: function (result) {

            if (result[0] == 1) {
                quiz_results_array = result[1];
                console.log(quiz_results_array);
            }
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

$(document).on('click', '.trivia-quiz-question .trivia-quiz-answer', function () {
    var post_id = $(this).attr('data-post-id');
    var question_id = $(this).attr('data-question-id');
    var answer_id = $(this).attr('data-answer-id');

    if (quiz_answers_array.length < 1) {
        return false;
    }
    if (jQuery.inArray(question_id, quiz_answered_questions) !== -1) {
        return false;
    }

    $('#quiz_question_' + question_id).addClass('quiz-answered');
    quiz_answered_questions.push(question_id);

    $('#question_answer_' + answer_id + ' .answer-icon').removeClass('icon-circle-outline');

    if (is_exist_quiz_question_answer(question_id, answer_id)) {
        //increasing correct answer count on each correct answer clicked
        quiz_correct_count++;
        $(this).addClass('correct-answer');
        $('#question_answer_' + answer_id + ' .answer-icon').addClass('icon-check-circle');
        $('#quiz_question_' + question_id + ' .alert-success').show();

        // console.log('Correct ans: '+quiz_correct_count)
    } else {
        var correct_answer_id = get_correct_answer_id(question_id);
        $(this).addClass('wrong-answer');
        $('#question_answer_' + answer_id + ' .answer-icon').addClass('icon-close-circle');
        //showing correct answer if wrong
        $('#question_answer_' + correct_answer_id).addClass('correct-answer');
        $('#question_answer_' + correct_answer_id + ' .answer-icon').removeClass('icon-circle-outline');
        $('#question_answer_' + correct_answer_id + ' .answer-icon').addClass('icon-check-circle');
        $('#quiz_question_' + question_id + ' .alert-danger').show();
    }

    //is last question answered
    var is_last_question = $('#quiz_question_' + question_id).attr('data-is-last');
    if (is_last_question == 1) {
        is_last_question_answered = true;
    }

    //scrolling for unanswered question and showing result
    if (is_last_question_answered) {
        var result_div = "result_container";
        //check if any question answer left
        $(".trivia-quiz-question").each(function (index) {
            if (!$(this).hasClass('quiz-answered')) {
                result_div = $(this).attr('id');
            }
        });
        if (result_div == "result_container") {
            setTimeout(function () {
                show_quiz_result();
            }, 800);
        }
        setTimeout(function () {
            $('html, body').animate({
                scrollTop: $("#" + result_div).offset().top
            }, 400);
        }, 600);
    }

});

//check quiz question answer
function is_exist_quiz_question_answer(question_id, answer_id) {
    var i;
    for (i = 0; i < quiz_answers_array.length; i++) {
        if (quiz_answers_array[i][0] == question_id) {
            if (quiz_answers_array[i][1] == answer_id) {
                return true;
            }
        }
    }
    return false;
}

//get correct answer id
function get_correct_answer_id(question_id) {
    var i;
    for (i = 0; i < quiz_answers_array.length; i++) {
        if (quiz_answers_array[i][0] == question_id) {
            return quiz_answers_array[i][1];
        }
    }
}

function show_quiz_result(){
    if (quiz_results_array.length > 0) {
        var result = "";
        var i;
        if (quiz_correct_count == 0) {
            var min_correct = quiz_results_array[0][1];

            var result = quiz_results_array[0][3];
            for (i = 0; i < quiz_results_array.length; i++) {
                if (min_correct > quiz_results_array[i][1]) {
                    var min_correct = quiz_results_array[i][1];
                    var result = quiz_results_array[i][3];

                    // console.log('min correct: '+ result)
                }
            }
        } else {
            for (i = 0; i < quiz_results_array.length; i++) {
                if (quiz_correct_count >= quiz_results_array[i][1] && quiz_correct_count <= quiz_results_array[i][2]) {
                    var result = quiz_results_array[i][3];
                }
            }
        }

        if (result.length > 0) {
            $('.result-show').removeClass('d-none');
            document.getElementById("correct_answer").innerHTML = quiz_correct_count;
            document.getElementById("wrong_answer").innerHTML = quiz_answered_questions.length - quiz_correct_count;
            $('#result_container').append(result);
        }
    }
    $('.btn-play-again').show().fadeIn(1000);
}

$(document).on('click', '.personality-quiz-question .personality-quiz-answer', function () {
    var post_id = $(this).attr('data-post-id');
    var question_id = $(this).attr('data-question-id');
    var answer_id = $(this).attr('data-answer-id');
    var assigned_result_id = $(this).attr('data-assigned-result-id');

    $('#quiz_question_' + question_id).addClass('quiz-answered');
    quiz_answered_questions.push(question_id);
    quiz_user_selected_answer.push(assigned_result_id);

    $('#question_answer_' + answer_id + ' .answer-icon').removeClass('icon-circle-outline');
    $(this).addClass('correct-answer');
    $('#question_answer_' + answer_id + ' .answer-icon').addClass('icon-check-circle');

    //is last question answered
    var is_last_question = $('#quiz_question_' + question_id).attr('data-is-last');
    if (is_last_question == 1) {
        is_last_question_answered = true;
    }

    //scrolling for unanswered question and showing result
    if (is_last_question_answered) {
        var result_div = "result_container";
        //check if any question answer left
        $(".personality-quiz-question").each(function (index) {
            if (!$(this).hasClass('quiz-answered')) {
                result_div = $(this).attr('id');
            }
        });
        if (result_div == "result_container") {
            setTimeout(function () {
                show_personality_quiz_result();
            }, 800);
        }
        setTimeout(function () {
            $('html, body').animate({
                scrollTop: $("#" + result_div).offset().top
            }, 400);
        }, 600);
    }
});

// showing personality quiz result
function show_personality_quiz_result(){
    var result_id = get_max_selected(quiz_user_selected_answer);
    console.log(quiz_user_selected_answer)
    var result;
    var i;
    if (result_id.length < 1) {
        result_id = quiz_user_answers[0];
    }
    for (i = 0; i < quiz_results_array.length; i++) {
        if (result_id == quiz_results_array[i][0]) {
            result = quiz_results_array[i][3];
            break;
        }
    }
    if (result.length > 0) {
        $('#result_container').append(result);
    }
    $('.btn-play-again').show();
}

// getting the max one
function get_max_selected(answers_array){
    var values = {}, max = 0, r;
    for (var a in answers_array) {
        values[answers_array[a]] = (values[answers_array[a]] || 0) + 1;
        if (values[answers_array[a]] > max) {
            max = values[answers_array[a]];
            r   = answers_array[a];
        }
    }
    for (var c in values) {
        if (values[c] == max) {
            return c;
        }
    }
}
