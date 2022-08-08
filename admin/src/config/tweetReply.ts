export const NAME_CHOICES = [
    {
        id: 'on_new_game',
        name: 'Tweet de réponse lors de la création d\'une nouvelle partie',
        placeholder: 'Hey %player_name% (%@userhandle%), thanks to participate to our game, to have more information about the game there is a website : %website_url% !'
    },
    {
        id: 'game_already_generated_less_than_a_day_ago',
        name: 'Tweet de réponse lorsque qu\'une partie a déjà été créée il y a moins d\'une journée pour ce joueur',
        placeholder: 'Thanks %player_name% (%@userhandle%) to talk about us. \nBut you already got a game link less than a day ago ! (talk again about us tomorrow to get a new game url). \nFor more information you can visit our website : %website_url%'
    },
    {
        id:'need_to_follow_us',
        name:'Tweet de réponse pour demander au joueur de suivre l\'un des comptes afin de pouvoir participer',
        placeholder: 'Thanks %player_name% (%@userhandle%) to talk about us. \nBut you are not yet eligible for the game, to be eligible you have to follow one of this accounts: @coopTilleuls or @ApiPlatform'
    }
];
