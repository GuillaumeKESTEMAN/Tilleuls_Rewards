import {FunctionComponent} from "react";
import {TwitterHashtag} from "../../types/TwitterHashtag";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {HASHTAG_PLACE_HOLDER} from '../../config/components/twitterHashtag.ts';

interface Props {
    twitterHashtag: TwitterHashtag;
}

export const Edit: FunctionComponent<Props> = ({twitterHashtag}) => (
    <EditGuesser {...twitterHashtag}>
        <InputGuesser source="hashtag" placeholder={HASHTAG_PLACE_HOLDER} disabled/>
        <InputGuesser source="active"/>
    </EditGuesser>
);
