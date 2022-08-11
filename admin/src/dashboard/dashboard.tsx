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
import {useMediaQuery, Theme} from '@mui/material';
import VideogameAssetIcon from '@mui/icons-material/VideogameAsset';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import RedeemIcon from '@mui/icons-material/Redeem';

interface State {
    nbLots: number;
    pendingLots?: LotRaRecord[];
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
    const isXSmall = useMediaQuery((theme: Theme) =>
        theme.breakpoints.down('sm')
    );

    const isSmall = useMediaQuery((theme: Theme) =>
        theme.breakpoints.down('lg')
    );
    const nbrMaxLotsToShow = isXSmall || isSmall ? 5 : 10;
    const nbrMaxGamesDatesToShow = isXSmall ? 7 : isSmall ? 15 : 30;

    const aSubDaysAgoForGames = useMemo(() => subDays(startOfDay(new Date()), nbrMaxGamesDatesToShow), [nbrMaxGamesDatesToShow]);

    const {data: lots = [], total: totalLots = 0} = useGetList<LotRaRecord>('lots', {
        sort: {field: 'quantity', order: 'ASC'},
        pagination: {page: 1, perPage: nbrMaxLotsToShow},
    });

    // @ts-ignore
    const lotsAggregation = useMemo<State>(() => {
        return {
            pendingLots: lots,
            nbLots: totalLots
        };
    }, [lots, totalLots]);

    const {data: games = [], total: totalGames = 0} = useGetList<Game>('games', {
        filter: {playDate: aSubDaysAgoForGames.toISOString()},
        sort: {field: 'playDate', order: 'DESC'},
        pagination: {page: 1, perPage: 100},
    });

    // @ts-ignore
    const gamesAggregation = useMemo<State>(() => {
        return {
            recentGames: games,
            nbGames: totalGames,
        };
    }, [games, totalGames]);


    const {total: totalPlayers = 0} = useGetList<PlayerRaRecord>('players', {
        filter: {"exists[lastPlayDate]": true},
        pagination: {page: 1, perPage: 1},
    });

    // @ts-ignore
    const playersAggregation = useMemo<State>(() => {
        return {
            nbPlayers: totalPlayers,
        };
    }, [totalPlayers]);

    const {recentGames, nbGames} = gamesAggregation;
    const {nbPlayers} = playersAggregation;
    const {nbLots, pendingLots} = lotsAggregation;

    return isXSmall ? (
        <Card>
            <NbResource
                value={nbLots}
                icon={RedeemIcon}
                title={translate('pos.dashboard.lots.total')}
            />
            <PendingLots nbLots={nbLots} lots={pendingLots}/>
            <br/>
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
            <GameChart games={recentGames} nbrMaxGamesDatesToShow={nbrMaxGamesDatesToShow}/>
        </Card>
    ) : isSmall ? (
        <Card>
            <div style={styles.flex}>
                <div style={styles.leftCol}>
                    <div style={styles.singleCol}>
                        <NbResource
                            value={nbLots}
                            icon={RedeemIcon}
                            title={translate('pos.dashboard.lots.total')}
                        />
                        <br/>
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
                    </div>
                </div>
                <div style={styles.rightCol}>
                    <div style={styles.singleCol}>
                        <PendingLots nbLots={nbLots} lots={pendingLots}/>
                    </div>
                </div>
            </div>
            <GameChart games={recentGames} nbrMaxGamesDatesToShow={nbrMaxGamesDatesToShow}/>
        </Card>
    ) : (
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
                        <GameChart games={recentGames} nbrMaxGamesDatesToShow={nbrMaxGamesDatesToShow}/>
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
                        <PendingLots nbLots={nbLots} lots={pendingLots}/>
                    </div>
                </div>
            </div>
        </Card>
    );
}

export default Dashboard;
