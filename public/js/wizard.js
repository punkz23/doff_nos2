$(document).ready(function(){
    $wizard_steps = $('.wizard_steps');
    $container = $('.container');

    $('.buttonPrevious').click(function(e){
        $selected_step = $wizard_steps.find('li').find('a.selected');
        $prev_step = $wizard_steps.find('li').has('a.selected').prev('li').find('a');

        $selected_step.removeClass('selected').addClass('done');
        $prev_step.removeClass('done').addClass('selected');

        $selected = $container.find('div.selected');
        $prev = $container.find('div.selected').prev('div.content');
        if($selected.hasClass('start')==false){
            $selected.removeClass('selected').addClass('unselect');
            $prev.removeClass('unselect').addClass('selected');
        }
        if($selected.hasClass('end')){
            $('.buttonNext').removeClass('buttonDisabled');
            $('.buttonFinish').addClass('buttonDisabled');
        }
        if($prev.hasClass('start')){
            $(this).addClass('buttonDisabled');
        }
        
    })

    $('.buttonNext').click(function(e){
        $selected_step = $wizard_steps.find('li').find('a.selected');
        $next_step = $wizard_steps.find('li').has('a.selected').next('li').find('a');
        
        $selected = $container.find('div.selected');
        $next = $container.find('div.selected').next('div.content');
        if(!$container.find('div.selected').find('form').valid()){
            e.preventDefault();
        }else{
            if($selected.hasClass('end')==false){
                $selected.removeClass('selected').addClass('unselect');
                $next.removeClass('unselect').addClass('selected');
                $('.buttonPrevious').removeClass('buttonDisabled');
            }
            if($next.removeClass('unselect').hasClass('end')){
                $(this).addClass('buttonDisabled');
                $('.buttonFinish').removeClass('buttonDisabled');
            }
            $selected_step.removeClass('selected').addClass('done');
            $proceed = true;
            if(parseInt($selected_step.find('.step_no')[0].innerHTML)==3){
                    
            }
            if($proceed==true){
                if($next_step.hasClass('disabled')){
                    $next_step.removeClass('disabled').addClass('selected');
                }else{
                    $next_step.removeClass('done').addClass('selected');
                }
            }
               
        }
    })
})