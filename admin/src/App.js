import Head from "next/head";
import {Navigate, Route} from "react-router-dom";
import {
  fetchHydra as baseFetchHydra,
  hydraDataProvider as baseHydraDataProvider, ResourceGuesser,
  useIntrospection,
} from "@api-platform/admin";
import {parseHydraDocumentation} from "@api-platform/api-doc-parser";
import authProvider from "./utils/authProvider.tsx";
import {LotCreate, LotShow, LotEdit, LotsList} from "./components/lot.ts";
import {MediaObjectList, MediaObjectCreate, MediaObjectEdit, MediaObjectShow} from "./components/mediaObject.ts";
import {TwitterHashtagCreate, TwitterHashtagEdit, TwitterHashtagsList} from "./components/twitterHashtag.ts";
import {RewardsList, RewardsShow, RewardsEdit} from "./components/reward.ts";
import {
  TwitterAccountToFollowCreate,
  TwitterAccountToFollowEdit,
  TwitterAccountToFollowList,
  TwitterAccountToFollowShow
} from "./components/twitterAccountToFollow.ts";
import {TweetRepliesList, TweetReplyCreate, TweetReplyEdit, TweetReplyShow} from "./components/tweetReply.ts";
import {API_ENTRYPOINT, ENTRYPOINT} from "./config/components/entrypoint.ts";
import {PlayerShow, PlayersList} from "./components/player.ts";
import RedeemIcon from '@mui/icons-material/Redeem';
import EmojiEventsIcon from '@mui/icons-material/EmojiEvents';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import PersonPinIcon from '@mui/icons-material/PersonPin';
import TagIcon from '@mui/icons-material/Tag';
import ReplyIcon from '@mui/icons-material/Reply';
import PanoramaIcon from '@mui/icons-material/Panorama';
import Dashboard from "./dashboard/dashboard.tsx";
import layout from "./utils/layout.tsx";
import i18nProvider from "./utils/i18nProvider.tsx";

const getHeaders = () => localStorage.getItem("token") ? {
  Authorization: `Bearer ${localStorage.getItem("token")}`,
} : {};

const fetchHydra = (url, options = {}) =>
  baseFetchHydra(url, {
    ...options,
    headers: getHeaders,
  });
const NavigateToLogin = () => {
  const introspect = useIntrospection();

  if (localStorage.getItem("token")) {
    introspect();
    return <></>;
  }
  return <Navigate to="/login"/>;
};

const apiDocumentationParser = async () => {
  try {
    return await parseHydraDocumentation(API_ENTRYPOINT, {headers: getHeaders});
  } catch (result) {
    const {api, response, status} = result;
    if (status !== 401 || !response) {
      throw result;
    }

    // Prevent infinite loop if the token is expired
    localStorage.removeItem("token");

    return {
      api,
      response,
      status,
      customRoutes: [
        <Route key="/" path="/" component={NavigateToLogin}/>
      ],
    };
  }
};

const dataProvider = baseHydraDataProvider({
  entrypoint: API_ENTRYPOINT,
  httpClient: fetchHydra,
  apiDocumentationParser,
  mercure: {
    jwt: process.env.REACT_APP_MERCURE_JWT,
    hub: ENTRYPOINT + "/.well-known/mercure"
  }
});

const AdminLoader = () => {
  if (typeof window !== "undefined") {
    const {HydraAdmin} = require("@api-platform/admin");
    return (<HydraAdmin dataProvider={dataProvider}
                        i18nProvider={i18nProvider}
                        layout={layout}
                        dashboard={Dashboard}
                        authProvider={authProvider}
                        entrypoint={API_ENTRYPOINT}>
      <ResourceGuesser name="lots"
                       icon={RedeemIcon}
                       list={LotsList}
                       show={LotShow}
                       create={LotCreate}
                       edit={LotEdit}/>
      <ResourceGuesser name="rewards"
                       icon={EmojiEventsIcon}
                       list={RewardsList}
                       show={RewardsShow}
                       edit={RewardsEdit}/>
      <ResourceGuesser name="players"
                       icon={AccountCircleIcon}
                       list={PlayersList}
                       show={PlayerShow}/>
      <ResourceGuesser name="twitter_account_to_follows"
                       icon={PersonPinIcon}
                       list={TwitterAccountToFollowList}
                       show={TwitterAccountToFollowShow}
                       create={TwitterAccountToFollowCreate}
                       edit={TwitterAccountToFollowEdit}/>
      <ResourceGuesser name="twitter_hashtags"
                       icon={TagIcon}
                       list={TwitterHashtagsList}
                       create={TwitterHashtagCreate}
                       edit={TwitterHashtagEdit}/>
      <ResourceGuesser name="tweet_replies"
                       icon={ReplyIcon}
                       list={TweetRepliesList}
                       create={TweetReplyCreate}
                       show={TweetReplyShow}
                       edit={TweetReplyEdit}/>
      <ResourceGuesser name="media_objects"
                       icon={PanoramaIcon}
                       list={MediaObjectList}
                       create={MediaObjectCreate}
                       show={MediaObjectShow}
                       edit={MediaObjectEdit}/>
    </HydraAdmin>);
  }

  return <></>;
};

const Admin = () => (
  <>
    <Head>
      <title>Tilleuls Rewards | Admin</title>
    </Head>

    <AdminLoader/>
  </>
);
export default Admin;
