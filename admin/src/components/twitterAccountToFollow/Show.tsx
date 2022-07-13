import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ReferenceField} from "react-admin";

interface Props {
    twitterAccountToFollow: TwitterAccountToFollow;
}

// @ts-ignore
export const Show: FunctionComponent<Props> = ({twitterAccountToFollow}) => (
    <ShowGuesser {...twitterAccountToFollow}>
        <FieldGuesser source="twitterAccountUsername" addLabel={true}/>
        <FieldGuesser source="twitterAccountName" addLabel={true}/>
        <FieldGuesser source="active" addLabel={true}/>
    </ShowGuesser>
);
