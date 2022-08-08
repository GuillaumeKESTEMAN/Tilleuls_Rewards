export const NAME_CHOICES = [
    {
        id: 'on_new_game',
        name: 'Tweet de réponse lors de la création d\'une nouvelle partie',
        placeholder: 'Hey %nom% (%@joueur%), merci de participer à notre jeu ! \nPour avoir plus d\'informations sur le jeu voici notre site web : %site_web%'
    },
    {
        id: 'game_already_generated_less_than_a_day_ago',
        name: 'Tweet de réponse lorsque qu\'une partie a déjà été créée il y a moins d\'une journée pour ce joueur',
        placeholder: 'Merci %nom% (%@joueur%) de parler de nous. \nMalheureusement tu as déjà joué il y a moins de 24h, tu pourras rejouer une fois que cela fera plus d\'une journée ! \nPour plus d\'informations tu peux consulter notre site web : %site_web%'
    },
    {
        id:'need_to_follow_us',
        name:'Tweet de réponse pour demander au joueur de suivre l\'un des comptes afin de pouvoir participer',
        placeholder: 'Merci %nom% (%@joueur%) de parler de nous. \nMalheureusement tu n\'es pas encore éligible pour pouvoir participer au jeu. Pour l\'être tu dois suivre au moins un des comptes nécessaires. \nPour plus d\'informations tu peux consulter notre site web : %site_web%'
    },
    {
        id:'no_more_available_lots',
        name:'Tweet de réponse lorsqu\'il n\'y a plus aucun lot de disponible',
        placeholder: 'Nous n\'avons malheureusement plus aucun lot de disponible... \nRetente ta chance un autre jour !'
    }
];

export const MESSAGE_HELPER = '%@joueur% = mentionne le joueur, %nom% = écrit le nom du joueur, %site_web% = écrit le site web enregistré'
