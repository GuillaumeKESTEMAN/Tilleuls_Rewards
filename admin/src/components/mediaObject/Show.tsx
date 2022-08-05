import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {FunctionField} from "react-admin";
// @ts-ignore
import {ENTRYPOINT} from "../../config/entrypoint.ts";

interface Props {
    mediaObject: MediaObject;
}

export const Show: FunctionComponent<Props> = ({mediaObject}) => {
    return (
        <ShowGuesser {...mediaObject}>
            <FieldGuesser source="name"/><br/>
            <FunctionField
                label="Image"
                render={record => {return <img style={{ width:"100%", maxWidth: "500px", maxHeight: "500px"}} src={ENTRYPOINT + "/image/" + record.filePath} alt={record.name} />;}}
            />
        </ShowGuesser>
    );
}
