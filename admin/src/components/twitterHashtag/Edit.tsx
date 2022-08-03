import {FunctionComponent} from "react";
import {TwitterHashtag} from "../../types/TwitterHashtag";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {HASHTAG_PLACE_HOLDER} from '../../config/twitterHashtag.ts';

interface Props {
    twitterHashtag: TwitterHashtag;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({twitterHashtag}) => (
    <EditGuesser {...twitterHashtag}>
        <InputGuesser source="hashtag" placeholder={HASHTAG_PLACE_HOLDER} disabled/>
        <InputGuesser source="active"/>
    </EditGuesser>
);
