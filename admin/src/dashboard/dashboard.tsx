// @ts-ignore
import React, {useMemo} from 'react';
import {Card} from '@mui/material';
import {useGetList, useTranslate} from 'react-admin';
import {subDays, startOfDay} from 'date-fns';
// @ts-ignore
import GameChart from "./gameChart.tsx";
// @ts-ignore
import NbResource from "./nbResource.tsx";
// @ts-ignore
import PendingLots from "./pendingLots.tsx";
import {Game} from '../types/Game';
import {PlayerRaRecord} from '../types/Player';
import {LotRaRecord} from '../types/Lot';
import VideogameAssetIcon from '@mui/icons-material/VideogameAsset';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import RedeemIcon from '@mui/icons-material/Redeem';

interface LotStats {
    nbLots: number;
}

interface GameStats {
    nbGames: number;
}

interface PlayerStats {
    nbPlayers: number;
}

interface State {
    nbLots: number;
    nbGames: number;
    recentGames?: Game[];
    nbPlayers: number;
}

const styles = {
    flex: {display: 'flex'},
    flexColumn: {display: 'flex', flexDirection: 'column'},
    leftCol: {flex: 1, marginRight: '0.5em'},
    rightCol: {flex: 1, marginLeft: '0.5em'},
    singleCol: {marginTop: '1em', marginBottom: '1em'},
};

const Dashboard = () => {
    const translate = useTranslate();
    const aMonthAgo = useMemo(() => subDays(startOfDay(new Date()), 30), []);

    const {total: totalLots} = useGetList<LotRaRecord>('lots', {
        pagination: {page: 1, perPage: 1},
    });

    // @ts-ignore
    const lotsAggregation = useMemo<State>(() => {
        if (!totalLots) return {
            nbLots: 0
        };
        return {
            nbLots: totalLots
        };
    }, [totalLots]);

    const {data: games} = useGetList<Game>('games', {
        filter: {playDate: aMonthAgo.toISOString()},
        sort: {field: 'playDate', order: 'DESC'},
        pagination: {page: 1, perPage: 20},
    });

    // @ts-ignore
    const gamesAggregation = useMemo<State>(() => {
        if (!games) return {};
        const aggregations = games
            .reduce(
                (stats: GameStats, game) => {
                    stats.nbGames++;
                    return stats;
                },
                {
                    nbGames: 0,
                }
            );
        return {
            recentGames: games,
            nbGames: aggregations.nbGames.toString(),
        };
    }, [games]);


    const {data: players} = useGetList<PlayerRaRecord>('players', {
        pagination: {page: 1, perPage: 20},
    });

    // @ts-ignore
    const playersAggregation = useMemo<State>(() => {
        if (!players) return {};
        const aggregations = players
            .filter(player => player.lastPlayDate !== null)
            .reduce(
                (stats: PlayerStats, player) => {
                    stats.nbPlayers++;
                    return stats;
                },
                {
                    nbPlayers: 0,
                }
            );
        return {
            nbPlayers: aggregations.nbPlayers.toString(),
        };
    }, [players]);

    const {recentGames, nbGames} = gamesAggregation;
    const {nbPlayers} = playersAggregation;
    const {nbLots} = lotsAggregation;

    return (
        <Card>
            <div style={styles.flex}>
                <div style={styles.leftCol}>
                    <div style={styles.singleCol}>
                        <NbResource
                            value={nbPlayers}
                            icon={AccountCircleIcon}
                            title={translate('pos.dashboard.players.total')}
                        />
                        <br/>
                        <NbResource
                            value={nbGames}
                            icon={VideogameAssetIcon}
                            title={translate('pos.dashboard.games.total')}
                        />
                        <br/>
                        <GameChart games={recentGames}/>
                    </div>
                </div>
                <div style={styles.rightCol}>
                    <div style={styles.singleCol}>
                        <NbResource
                            value={nbLots}
                            icon={RedeemIcon}
                            title={translate('pos.dashboard.lots.total')}
                        />
                        <br/>
                        <PendingLots nbLots={nbLots} />
                    </div>
                </div>
            </div>
        </Card>
    );
}

export default Dashboard;
