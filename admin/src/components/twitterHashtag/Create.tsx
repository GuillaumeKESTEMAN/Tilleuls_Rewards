import {FunctionComponent} from "react";
import {TwitterHashtag} from "../../types/TwitterHashtag";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {HASHTAG_PLACE_HOLDER} from '../../config/twitterHashtag.ts';

interface Props {
    twitterHashtag: TwitterHashtag;
}

export const Create: FunctionComponent<Props> = ({twitterHashtag}) => (
    <CreateGuesser {...twitterHashtag}>
        <InputGuesser source="hashtag" placeholder={HASHTAG_PLACE_HOLDER}/>
        <InputGuesser source="active"/>
    </CreateGuesser>
);
