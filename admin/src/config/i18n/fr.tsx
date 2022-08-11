import frenchMessages from 'ra-language-french';

export const fr = {
    ...frenchMessages,
    pos: {
        dashboard: {
            all_lots: 'Voir tous les lots',
            lots: {
                pending: 'Stocks des lots :',
                total: 'Nombre total de lots : ',
            },
            games: {
                month_history: 'Nombre de parties créées sur %nbr_jours% jours :',
                no_games: 'Pas de parties existantes',
                total: 'Nombre total de parties : '
            },
            players: {
                total: 'Nombre total de joueurs : ',
            },
        },
    },
    resources: {
        lots: {
            name: 'Lot |||| Lots',
            fields: {
                name: 'Nom',
                quantity: 'Quantité',
                message: 'Message',
                image: 'Image'
            },
        },
        rewards: {
            name: 'Récompense |||| Récompenses',
            fields: {
                lot: 'Lot',
                game: 'Partie',
                player: 'Joueur',
                distributed: 'Distribué'
            },
        },
        players: {
            name: 'Joueur |||| Joueurs',
            fields: {
                name: 'Nom',
                username: 'Pseudo',
                twitterAccountId: 'ID du compte Twitter',
                lastPlayDate: 'Dernière date de jeu',
                tweets: 'Tweets'
            },
            list: {
                active_last_play_date: 'Joueurs actifs'
            }
        },
        twitter_account_to_follows: {
            name: 'Compte Twitter à suivre |||| Comptes Twitter à suivre',
            fields: {
                name: 'Nom',
                username: 'Pseudo',
                twitterAccountId: 'ID du compte Twitter',
                active: 'Activé'
            },
        },
        twitter_hashtags: {
            name: 'Hashtag |||| Hashtags',
            fields: {
                hashtag: 'Hashtag',
                active: 'Activé'
            },
        },
        tweet_replies: {
            name: 'Tweet de réponse |||| Tweets de réponses',
            fields: {
                name: 'A quoi ça sert ?',
                message: 'Message'
            },
        },
        media_objects: {
            name: 'Image |||| Images',
            fields: {
                name: 'Nom',
                contentUrl: 'URL',
                file: 'Image',
                filePath: 'Chemin du fichier'
            },
        }
    },
};
