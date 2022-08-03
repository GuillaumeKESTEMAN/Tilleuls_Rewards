import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {NAME_PLACE_HOLDER} from '../../config/twitterAccountToFollow.ts';

interface Props {
    twitterAccountToFollow: TwitterAccountToFollow;
}

// @ts-ignore
export const Create: FunctionComponent<Props> = ({twitterAccountToFollow}) => (
    <CreateGuesser {...twitterAccountToFollow}>
        <InputGuesser source="twitterAccountUsername" placeholder={NAME_PLACE_HOLDER}/>
        <InputGuesser source="active"/>
    </CreateGuesser>
);
