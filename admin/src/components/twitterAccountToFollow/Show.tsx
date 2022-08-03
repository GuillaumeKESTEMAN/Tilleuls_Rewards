import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

interface Props {
    twitterAccountToFollow: TwitterAccountToFollow;
}

// @ts-ignore
export const Show: FunctionComponent<Props> = ({twitterAccountToFollow}) => (
    <ShowGuesser {...twitterAccountToFollow}>
        <FieldGuesser source="twitterAccountUsername"/>
        <FieldGuesser source="twitterAccountName"/>
        <FieldGuesser source="active"/>
    </ShowGuesser>
);
