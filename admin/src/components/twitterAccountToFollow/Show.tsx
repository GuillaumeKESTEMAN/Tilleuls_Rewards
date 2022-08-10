import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

interface Props {
    twitterAccountToFollow: TwitterAccountToFollow;
}

export const Show: FunctionComponent<Props> = ({twitterAccountToFollow}) => (
    <ShowGuesser {...twitterAccountToFollow}>
        <FieldGuesser source="username"/>
        <FieldGuesser source="name"/>
        <FieldGuesser source="active"/>
    </ShowGuesser>
);
