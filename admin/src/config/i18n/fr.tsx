import frenchMessages from 'ra-language-french';

export const fr = {
    ...frenchMessages,
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
