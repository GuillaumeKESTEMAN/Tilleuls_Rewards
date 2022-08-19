import * as React from 'react';
import {Card, CardHeader, CardContent} from '@mui/material';
import {
    ResponsiveContainer,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    BarChart,
    Bar
} from 'recharts';
import {useTranslate} from 'react-admin';
import {format, subDays, addDays} from 'date-fns';

import {Stat_Game} from '../types/Stat_Game';


const GameChart = (props: { gamesCount?: Stat_Game[], nbrMaxGamesDatesToShow?: number }) => {
    const {gamesCount, nbrMaxGamesDatesToShow} = props;
    const translate = useTranslate();

    const lastDay = new Date();
    const lastMonthDays = Array.from({length: nbrMaxGamesDatesToShow}, (_, i) => subDays(lastDay, i));
    const aMonthAgo = subDays(new Date(), nbrMaxGamesDatesToShow);

    const dateFormatter = (date: number): string =>
        new Date(date).toLocaleDateString();

    const aggregateGamesByDay = (gamesCount: Stat_Game[]): { [key: string]: number } =>
        gamesCount
            .reduce((acc, curr) => {
                const day = format(new Date(curr.date), 'yyyy-MM-dd');
                acc[day] = curr.nbrGames;
                return acc;
            }, {} as { [key: string]: number });

    const getNbGamesPerDay = (gamesCount: Stat_Game[]): TotalByDay[] => {
        const daysWithGames = aggregateGamesByDay(gamesCount);
        return lastMonthDays.map(date => ({
            date: date.getTime(),
            total: daysWithGames[format(new Date(date), 'yyyy-MM-dd')] || 0,
        }));
    };

    if (!gamesCount || (Array.isArray(gamesCount) && gamesCount.length === 0)) return (<CardHeader title={translate('pos.dashboard.games.no_games')}/>);

    return (
        <Card>
            <CardHeader
                title={translate('pos.dashboard.games.month_history').replace('%nbr_jours%', nbrMaxGamesDatesToShow.toString())}/>
            <CardContent>
                <div style={{width: '100%', height: 300}}>
                    <ResponsiveContainer>
                        <BarChart width={600} height={600} data={getNbGamesPerDay(gamesCount)}>
                            <CartesianGrid stroke="#ccc"/>
                            <Bar dataKey="total" fill="#288690"/>
                            <XAxis
                                dataKey="date"
                                name="Date"
                                type="number"
                                scale="time"
                                domain={[
                                    addDays(aMonthAgo, 0).getTime(),
                                    new Date().getTime(),
                                ]}
                                tickFormatter={dateFormatter}
                            />
                            <YAxis dataKey="total" name="Game"/>
                            <Tooltip
                                cursor={{strokeDasharray: '3 3'}}
                                formatter={(value: any) =>
                                    new Intl.NumberFormat('fr').format(value)
                                }
                                labelFormatter={(label: any) =>
                                    dateFormatter(label)
                                }
                                contentStyle={{borderColor: '#288690'}}
                            />
                        </BarChart>
                    </ResponsiveContainer>
                </div>
            </CardContent>
        </Card>
    );
};

interface TotalByDay {
    date: number;
    total: number;
}

export default GameChart;
