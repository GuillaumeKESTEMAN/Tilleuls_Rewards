import { RaRecord } from 'react-admin';

export interface Game extends RaRecord {
    tweet?: any;
    player?: any;
    score?: number;
    playDate?: Date;
    reward?: any;
}
